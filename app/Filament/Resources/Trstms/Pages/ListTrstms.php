<?php

namespace App\Filament\Resources\Trstms\Pages;

use App\Filament\Resources\Trstms\TrstmResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTrstms extends ListRecords
{
    protected static string $resource = TrstmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
