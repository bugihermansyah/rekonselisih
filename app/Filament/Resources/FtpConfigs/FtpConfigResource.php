<?php

namespace App\Filament\Resources\FtpConfigs;

use App\Filament\Resources\FtpConfigs\Pages\CreateFtpConfig;
use App\Filament\Resources\FtpConfigs\Pages\EditFtpConfig;
use App\Filament\Resources\FtpConfigs\Pages\ListFtpConfigs;
use App\Filament\Resources\FtpConfigs\Schemas\FtpConfigForm;
use App\Filament\Resources\FtpConfigs\Tables\FtpConfigsTable;
use App\Models\FtpConfig;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FtpConfigResource extends Resource
{
    protected static ?string $model = FtpConfig::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return FtpConfigForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FtpConfigsTable::configure($table);
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
            'index' => ListFtpConfigs::route('/'),
            'create' => CreateFtpConfig::route('/create'),
            'edit' => EditFtpConfig::route('/{record}/edit'),
        ];
    }
}
