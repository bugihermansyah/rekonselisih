<?php

namespace App\Jobs;

use App\Models\FtpConfig;
use App\Models\FtpUploadFile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessFtpUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // 1. Deklarasikan property di sini agar bisa diakses $this->...
    public $ftpConfigId;
    public $fileLogId;
    public $localPath;

    /**
     * 2. Tangkap data dari dispatch() ke dalam constructor
     */
    public function __construct($ftpConfigId, $fileLogId, $localPath)
    {
        $this->ftpConfigId = $ftpConfigId;
        $this->fileLogId = $fileLogId;
        $this->localPath = $localPath;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $fileLog = FtpUploadFile::find($this->fileLogId);
        $config = FtpConfig::find($this->ftpConfigId);

        try {
            // --- LOGIKA RENAME ---
            // Contoh: 20250926000000...txt -> 20250926 + Hms + ...txt
            $originalName = $fileLog->filename_original;
            $datePart = substr($originalName, 0, 8); // 20250926
            $uniqueTime = now()->format('His');      // JamMenitDetik Baru
            $restOfName = substr($originalName, 14); // Sisanya setelah 14 karakter awal

            $newName = $datePart . $uniqueTime . $restOfName;

            // --- DYNAMIC FTP CONFIG ---
            config(['filesystems.disks.temp_ftp' => [
                'driver'   => 'ftp',
                'host'     => $config->host,
                'username' => $config->username,
                'password' => $config->password,
                'port'     => (int) $config->port,
                'root'     => $config->path ?? '/',
                'passive'  => true, // WAJIB untuk FileZilla Server di Windows
                'ignorePassiveAddress' => true, // Tambahkan ini jika server di balik NAT
                'timeout'  => 30,
            ]]);

            // --- PROSES UPLOAD ---
            $content = Storage::disk('local')->get($this->localPath);

            if (Storage::disk('temp_ftp')->put($newName, $content)) {
                $fileLog->update([
                    'filename_ftp' => $newName,
                    'status' => 'success'
                ]);
                // Hapus file sementara di local
                Storage::disk('local')->delete($this->localPath);
            }
        } catch (\Exception $e) {
            $fileLog->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
        }
    }
}
