<?php

namespace App\Filament\Resources\Stis\Pages;

use App\Filament\Resources\Stis\StiResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSti extends EditRecord
{
    protected static string $resource = StiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
