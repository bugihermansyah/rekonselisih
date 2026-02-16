<?php

namespace App\Filament\Resources\Stis\Pages;

use App\Filament\Resources\Stis\StiResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSti extends ViewRecord
{
    protected static string $resource = StiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
