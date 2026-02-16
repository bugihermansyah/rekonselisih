<?php

namespace App\Filament\Resources\FtpConfigs\Pages;

use App\Filament\Resources\FtpConfigs\FtpConfigResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFtpConfigs extends ListRecords
{
    protected static string $resource = FtpConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
