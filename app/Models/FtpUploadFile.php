<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FtpUploadFile extends Model
{
    protected $fillable = [
        'ftp_upload_id',
        'filename_original',
        'filename_ftp',
        'status',
        'error_message',
    ];

    public function ftpUpload(): BelongsTo
    {
        return $this->belongsTo(FtpUpload::class);
    }
}
