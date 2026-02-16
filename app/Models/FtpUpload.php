<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class FtpUpload extends Model
{
    protected $fillable = [
        'ftp_config_id',
        'user_id',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ftpConfig(): BelongsTo
    {
        return $this->belongsTo(FtpConfig::class);
    }

    public function ftpUploadFiles(): HasMany
    {
        return $this->hasMany(FtpUploadFile::class);
    }
}
