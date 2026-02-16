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
use League\Flysystem\Filesystem;
use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\Ftp\FtpConnectionOptions;

class UploadToFtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;
    public array $backoff = [10, 30, 60];

    public function __construct(
        public int $ftpUploadFileId,
        public string $filePath,
    ) {
        $this->onQueue('ftp-upload');
    }

    public function handle(): void
    {
        Log::info("Job Started: UploadToFtpJob for File ID: {$this->ftpUploadFileId}");

        $fileRecord = FtpUploadFile::with('ftpUpload.ftpConfig', 'ftpUpload.user')
            ->find($this->ftpUploadFileId);

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

            if (! $ftpConfig) {
                throw new \Exception("FTP Config not found.");
            }

            $originalFilename = $fileRecord->filename_original;
            $newFilename = $this->generateNewFilename($originalFilename);

            /*
            |--------------------------------------------------------------------------
            | Build Flysystem FTP (NativePHP Safe - No ext-ftp Required)
            |--------------------------------------------------------------------------
            */
            $options = FtpConnectionOptions::fromArray([
                'host' => $ftpConfig->host,
                'port' => $ftpConfig->port ?? 21,
                'username' => $ftpConfig->username,
                'password' => $ftpConfig->password,
                'root' => $ftpConfig->path ?? '/',
                'passive' => true,
                'ssl' => false,
                'timeout' => 30,
            ]);

            $adapter = new FtpAdapter($options);
            $filesystem = new Filesystem($adapter);

            /*
            |--------------------------------------------------------------------------
            | Upload via Stream (Memory Safe)
            |--------------------------------------------------------------------------
            */
            $stream = fopen($this->filePath, 'r');

            if (! $stream) {
                throw new \Exception("Failed to open local file.");
            }

            $filesystem->writeStream($newFilename, $stream);

            if (is_resource($stream)) {
                fclose($stream);
            }

            /*
            |--------------------------------------------------------------------------
            | Cleanup
            |--------------------------------------------------------------------------
            */
            @unlink($this->filePath);

            $fileRecord->update([
                'status' => 'SUCCESS',
                'filename_ftp' => $newFilename,
                'error_message' => null,
            ]);

            Log::info("FTP Upload Success: {$newFilename}");
        } catch (\Throwable $e) {

            $fileRecord->update([
                'status' => 'FAILED',
                'error_message' => $e->getMessage(),
            ]);

            Log::error("FTP Upload Failed (File ID: {$this->ftpUploadFileId}): " . $e->getMessage());

            throw $e; // biar retry jalan
        }

        $this->checkBatchCompletion($fileRecord);
    }

    /*
    |--------------------------------------------------------------------------
    | Batch Completion Check
    |--------------------------------------------------------------------------
    */
    private function checkBatchCompletion(FtpUploadFile $currentFile): void
    {
        try {
            $batchId = $currentFile->ftp_upload_id;

            $pendingCount = FtpUploadFile::query()
                ->where('ftp_upload_id', $batchId)
                ->whereIn('status', ['PENDING', 'PROCESSING'])
                ->count();

            if ($pendingCount === 0) {

                $user = $currentFile->ftpUpload->user;

                if ($user) {

                    $stats = FtpUploadFile::query()
                        ->where('ftp_upload_id', $batchId)
                        ->select(
                            DB::raw('count(*) as total'),
                            DB::raw("sum(case when status = 'SUCCESS' then 1 else 0 end) as success_count"),
                            DB::raw("sum(case when status = 'FAILED' then 1 else 0 end) as failed_count")
                        )
                        ->first();

                    $message = "Upload Selesai. Total: {$stats->total}, "
                        . "Sukses: {$stats->success_count}, "
                        . "Gagal: {$stats->failed_count}.";

                    Notification::make()
                        ->title('FTP Upload Completed')
                        ->success()
                        ->body($message)
                        ->sendToDatabase($user);

                    Log::info("Batch completed. Notification sent.");
                }
            }
        } catch (\Throwable $e) {
            Log::error("Batch completion error: " . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Filename Generator
    |--------------------------------------------------------------------------
    */
    private function generateNewFilename(string $originalFilename): string
    {
        if (strlen($originalFilename) < 14) {
            return 'RENAMED_' . time() . '_' . $originalFilename;
        }

        $datePart = substr($originalFilename, 0, 8);
        $restOfFile = substr($originalFilename, 14);
        $newTimePart = now()->format('His');

        return $datePart . $newTimePart . $restOfFile;
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Display Name (Optional - For Horizon / Logs)
    |--------------------------------------------------------------------------
    */
    public function displayName(): string
    {
        return 'FTP Upload - File ID: ' . $this->ftpUploadFileId;
    }
}
