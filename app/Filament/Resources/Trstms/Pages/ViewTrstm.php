<?php

namespace App\Filament\Resources\Trstms\Pages;

use App\Filament\Resources\Trstms\TrstmResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTrstm extends ViewRecord
{
    protected static string $resource = TrstmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
