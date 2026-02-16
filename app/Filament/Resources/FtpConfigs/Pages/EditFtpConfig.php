<?php

namespace App\Filament\Resources\FtpConfigs\Pages;

use App\Filament\Resources\FtpConfigs\FtpConfigResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFtpConfig extends EditRecord
{
    protected static string $resource = FtpConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
