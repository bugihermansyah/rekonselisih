<?php

namespace App\Filament\Resources\Stis\Pages;

use App\Filament\Resources\Stis\StiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStis extends ListRecords
{
    protected static string $resource = StiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
