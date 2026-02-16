<?php

namespace App\Filament\Resources\Trstms\Tables;

use App\Filament\Imports\TrstmImporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ImportAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TrstmsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->headerActions([
                ImportAction::make()
                    ->importer(TrstmImporter::class)
            ])
            ->columns([
                TextColumn::make('trs_id')
                    ->label('TrsID')
                    ->searchable(),
                TextColumn::make('trstm_date')
                    ->label('TrsTmDate')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('emoney_type')
                    ->label('EmoneyType')
                    ->searchable(),
                TextColumn::make('card_no')
                    ->label('CardNo')
                    ->searchable(),
                TextColumn::make('mid')
                    ->label('MID')
                    ->searchable(),
                TextColumn::make('terminal_id')
                    ->label('TerminalID')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('balance')
                    ->label('Balance')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('counter')
                    ->label('Counter')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('bank_type')
                    ->label('BankType')
                    ->searchable(),
                TextColumn::make('user_id')
                    ->label('UserID')
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
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
