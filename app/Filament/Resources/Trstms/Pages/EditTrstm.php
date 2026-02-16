<?php

namespace App\Filament\Resources\Trstms\Pages;

use App\Filament\Resources\Trstms\TrstmResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTrstm extends EditRecord
{
    protected static string $resource = TrstmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
