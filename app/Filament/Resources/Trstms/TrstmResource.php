<?php

namespace App\Filament\Resources\Trstms;

use App\Filament\Resources\Trstms\Pages\CreateTrstm;
use App\Filament\Resources\Trstms\Pages\EditTrstm;
use App\Filament\Resources\Trstms\Pages\ListTrstms;
use App\Filament\Resources\Trstms\Pages\ViewTrstm;
use App\Filament\Resources\Trstms\Schemas\TrstmForm;
use App\Filament\Resources\Trstms\Schemas\TrstmInfolist;
use App\Filament\Resources\Trstms\Tables\TrstmsTable;
use App\Models\Trstm;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TrstmResource extends Resource
{
    protected static ?string $model = Trstm::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'trs_id';

    public static function form(Schema $schema): Schema
    {
        return TrstmForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TrstmInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrstmsTable::configure($table);
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
            'index' => ListTrstms::route('/'),
            'create' => CreateTrstm::route('/create'),
            'view' => ViewTrstm::route('/{record}'),
            'edit' => EditTrstm::route('/{record}/edit'),
        ];
    }
}
