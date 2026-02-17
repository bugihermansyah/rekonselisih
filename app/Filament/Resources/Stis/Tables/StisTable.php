<?php

namespace App\Filament\Resources\Stis\Tables;

use App\Filament\Imports\StiImporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ImportAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->headerActions([
                ImportAction::make()
                    ->chunkSize(2500)
                    ->importer(StiImporter::class)
            ])
            ->columns([
                TextColumn::make('card_type')
                    ->label('Type')
                    ->searchable(),
                TextColumn::make('terminal_id')
                    ->label('Terminal ID')
                    ->searchable(),
                TextColumn::make('terminal')
                    ->label('Terminal')
                    ->searchable(),
                TextColumn::make('card_no')
                    ->label('Card No')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('balance')
                    ->label('Balance')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('trans_date')
                    ->label('Transaction Date')
                    ->dateTime('d-m-Y H:i:s')
                    ->sortable(),
                TextColumn::make('ftp_file')
                    ->label('FTP File')
                    ->searchable(),
                TextColumn::make('created_date')
                    ->label('Created Date')
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('settle_date')
                    ->label('Settle Date')
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('response')
                    ->label('Response')
                    ->searchable(),
                TextColumn::make('filename')
                    ->label('Filename')
                    ->searchable(),
                TextColumn::make('bank_mid')
                    ->label('Bank MID')
                    ->searchable(),
                TextColumn::make('bank_tid')
                    ->label('Bank TID')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
