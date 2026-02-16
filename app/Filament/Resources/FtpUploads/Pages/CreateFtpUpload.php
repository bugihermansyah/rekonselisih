<?php

namespace App\Filament\Resources\FtpUploads\Pages;

use App\Filament\Resources\FtpUploads\FtpUploadResource;
use App\Jobs\ProcessFtpUpload;
use Filament\Resources\Pages\CreateRecord;

class CreateFtpUpload extends CreateRecord
{
    protected static string $resource = FtpUploadResource::class;
}
