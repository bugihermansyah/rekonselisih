<?php

namespace App\Filament\Resources\FtpUploads;

use App\Filament\Resources\FtpUploads\Pages\CreateFtpUpload;
use App\Filament\Resources\FtpUploads\Pages\EditFtpUpload;
use App\Filament\Resources\FtpUploads\Pages\ListFtpUploads;
use App\Filament\Resources\FtpUploads\RelationManagers\FtpUploadFilesRelationManager;
use App\Models\FtpUpload;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FtpUploadResource extends Resource
{
    protected static ?string $model = FtpUpload::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCloudArrowUp;

    protected static ?string $navigationLabel = 'FTP Batch Upload';

    protected static string|\UnitEnum|null $navigationGroup = 'FTP';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        Select::make('ftp_config_id')
                            ->label('FTP Config')
                            ->relationship('ftpConfig', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Textarea::make('description')
                            ->label('Description')
                            ->maxLength(500),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ftpConfig.name')
                    ->label('FTP Config')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('ftp_upload_files_count')
                    ->counts('ftpUploadFiles')
                    ->label('Files'),
                TextColumn::make('created_at')
                    ->dateTime('d-m-Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            FtpUploadFilesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFtpUploads::route('/'),
            'create' => CreateFtpUpload::route('/create'),
            'edit' => EditFtpUpload::route('/{record}/edit'),
        ];
    }
}
