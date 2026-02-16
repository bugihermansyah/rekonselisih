<?php

namespace App\Filament\Resources\Stis;

use App\Filament\Resources\Stis\Pages\CreateSti;
use App\Filament\Resources\Stis\Pages\EditSti;
use App\Filament\Resources\Stis\Pages\ListStis;
use App\Filament\Resources\Stis\Pages\ViewSti;
use App\Filament\Resources\Stis\Schemas\StiForm;
use App\Filament\Resources\Stis\Schemas\StiInfolist;
use App\Filament\Resources\Stis\Tables\StisTable;
use App\Models\Sti;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StiResource extends Resource
{
    protected static ?string $model = Sti::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return StiForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StiInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStis::route('/'),
            'create' => CreateSti::route('/create'),
            'view' => ViewSti::route('/{record}'),
            'edit' => EditSti::route('/{record}/edit'),
        ];
    }
}
