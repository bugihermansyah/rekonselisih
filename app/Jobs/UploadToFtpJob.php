<?php

namespace App\Jobs;

use App\Models\FtpUploadFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class UploadToFtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $ftpUploadFileId,
        public string $filePath,
    ) {}

    public function handle(): void
    {
        Log::info("Job Started: UploadToFtpJob for File ID: {$this->ftpUploadFileId}");

        // Find the file record with its parent Batch and Config
        $fileRecord = FtpUploadFile::with('ftpUpload.ftpConfig', 'ftpUpload.user')->find($this->ftpUploadFileId);

        if (! $fileRecord) {
            Log::error("FtpUploadFile not found: {$this->ftpUploadFileId}");
            return;
        }

        $fileRecord->update(['status' => 'PROCESSING']);

        try {
            if (! file_exists($this->filePath)) {
                throw new \Exception("Local file not found: {$this->filePath}");
            }

            $ftpConfig = $fileRecord->ftpUpload->ftpConfig;
            if (!$ftpConfig) {
                throw new \Exception("FTP Config not found for this batch.");
            }

            $originalFilename = $fileRecord->filename_original;
            $newFilename = $this->generateNewFilename($originalFilename);

            // Configure dynamic FTP disk
            $config = [
                'driver' => 'ftp',
                'host' => $ftpConfig->host,
                'port' => $ftpConfig->port,
                'username' => $ftpConfig->username,
                'password' => $ftpConfig->password,
                'root' => $ftpConfig->path ?? '/',
                'passive' => true,
            ];

            // Log::info("FTP Config: " . json_encode(collect($config)->except(['password'])->toArray()));

            $disk = Storage::build($config);

            // Upload
            $fileContent = file_get_contents($this->filePath);
            $disk->put($newFilename, $fileContent);

            // Cleanup local file
            unlink($this->filePath);

            $fileRecord->update([
                'status' => 'SUCCESS',
                'filename_ftp' => $newFilename,
            ]);
            Log::info("File Upload Success: {$newFilename}");
        } catch (\Exception $e) {
            $fileRecord->update([
                'status' => 'FAILED',
                'error_message' => $e->getMessage(),
            ]);
            Log::error("FTP Upload Job Failed (File ID: {$this->ftpUploadFileId}): " . $e->getMessage());
        }

        // Check for batch completion and notify
        $this->checkBatchCompletion($fileRecord);
    }

    private function checkBatchCompletion(FtpUploadFile $currentFile): void
    {
        try {
            $batchId = $currentFile->ftp_upload_id;

            // Check if any other files in this batch are still pending or processing
            $pendingCount = FtpUploadFile::query()
                ->where('ftp_upload_id', $batchId)
                ->whereIn('status', ['PENDING', 'PROCESSING'])
                ->count();

            Log::info("Checking batch completion for Batch ID: {$batchId}. Pending count: {$pendingCount}");

            if ($pendingCount === 0) {
                $user = $currentFile->ftpUpload->user;

                if ($user) {
                    // Calculate statistics
                    $stats = FtpUploadFile::query()
                        ->where('ftp_upload_id', $batchId)
                        ->select(
                            DB::raw('count(*) as total'),
                            DB::raw("sum(case when status = 'SUCCESS' then 1 else 0 end) as success_count"),
                            DB::raw("sum(case when status = 'FAILED' then 1 else 0 end) as failed_count")
                        )
                        ->first();

                    $success = $stats->success_count ?? 0;
                    $failed = $stats->failed_count ?? 0;
                    $total = $stats->total ?? 0;

                    $message = "Upload Selesai. Total: {$total}, Sukses: {$success}, Gagal: {$failed}.";

                    // Filament Database Notification
                    Notification::make()
                        ->title('FTP Upload Completed')
                        ->success()
                        ->body($message)
                        ->sendToDatabase($user);

                    Log::info("Batch completion notification sent to User ID: {$user->id} for Batch ID: {$batchId}");
                } else {
                    Log::warning("Batch completed but no User found for Batch ID: {$batchId}");
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed to send batch completion notification: " . $e->getMessage());
        }
    }

    private function generateNewFilename(string $originalFilename): string
    {
        // Expected format: YYYYMMDDHHmmss... (14 chars prefix)
        // We keep YYYYMMDD (first 8), replace HHmmss (next 6) with current time

        if (strlen($originalFilename) < 14) {
            return 'RENAMED_' . time() . '_' . $originalFilename;
        }

        $datePart = substr($originalFilename, 0, 8); // YYYYMMDD
        // $timePart = substr($originalFilename, 8, 6); // HHmmss (to be replaced)
        $restOfFile = substr($originalFilename, 14);

        $newTimePart = now()->format('His'); // Current HHmmss

        return $datePart . $newTimePart . $restOfFile;
    }
}
