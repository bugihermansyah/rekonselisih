<?php

namespace App\Filament\Resources\FtpUploads\Pages;

use App\Filament\Resources\FtpUploads\FtpUploadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFtpUploads extends ListRecords
{
    protected static string $resource = FtpUploadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
