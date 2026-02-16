<?php

namespace App\Filament\Imports;

use App\Models\Trstm;
use Carbon\Carbon;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class TrstmImporter extends Importer
{
    protected static ?string $model = Trstm::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('trs_id')
                ->label('TrsID'),
            ImportColumn::make('trstm_date')
                ->label('TrsTmDate')
                ->castStateUsing(function (?string $state): ?string {
                    if (blank($state)) return null;
                    try {
                        return Carbon::parse($state)->format('Y-m-d H:i:s');
                    } catch (\Exception $e) {
                        return null;
                    }
                }),
            ImportColumn::make('emoney_type')
                ->label('EmoneyType'),
            ImportColumn::make('card_no')
                ->label('CardNo'),
            ImportColumn::make('mid')
                ->label('MID'),
            ImportColumn::make('terminal_id')
                ->label('TerminalID'),
            ImportColumn::make('amount')
                ->numeric()
                ->rules(['integer'])
                ->label('Amount'),
            ImportColumn::make('balance')
                ->numeric()
                ->rules(['integer'])
                ->label('Balance'),
            ImportColumn::make('log')
                ->label('Log'),
            ImportColumn::make('inv')
                ->label('Inv'),
            ImportColumn::make('counter')
                ->numeric()
                ->rules(['integer'])
                ->label('Counter'),
            ImportColumn::make('bank_type')
                ->label('BankType'),
            ImportColumn::make('user_id')
                ->label('UserID'),
        ];
    }

    public function resolveRecord(): Trstm
    {
        return Trstm::firstOrNew([
            'trs_id' => $this->data['trs_id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your trstm import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
