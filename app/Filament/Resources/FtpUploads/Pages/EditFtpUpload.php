<?php

namespace App\Filament\Resources\FtpUploads\Pages;

use App\Filament\Resources\FtpUploads\FtpUploadResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFtpUpload extends EditRecord
{
    protected static string $resource = FtpUploadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // DeleteAction::make(),
        ];
    }
}
