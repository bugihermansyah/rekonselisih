<?php

namespace App\Filament\Imports;

use App\Models\Sti;
use Carbon\Carbon;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class StiImporter extends Importer
{
    protected static ?string $model = Sti::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('card_type')
                ->label('CardType'),
            ImportColumn::make('terminal_id')
                ->label('TerminalID'),
            ImportColumn::make('terminal')
                ->label('Terminal'),
            ImportColumn::make('card_no')
                ->label('CardNo'),
            ImportColumn::make('amount')
                ->numeric(),
            ImportColumn::make('balance')
                ->numeric(),
            ImportColumn::make('trans_date')
                ->label('TransDate')
                ->castStateUsing(function (?string $state): ?string {
                    if (blank($state)) return null;
                    try {
                        return Carbon::parse($state)->format('Y-m-d H:i:s');
                    } catch (\Exception $e) {
                        return null;
                    }
                }),
            ImportColumn::make('ftp_file')
                ->label('FTPFile'),
            ImportColumn::make('created_date')
                ->label('CreatedDate')
                ->castStateUsing(function (?string $state): ?string {
                    if (blank($state)) return null;
                    try {
                        return Carbon::parse($state)->format('Y-m-d');
                    } catch (\Exception $e) {
                        return null;
                    }
                }),
            ImportColumn::make('settle_date')
                ->label('SettleDate')
                ->castStateUsing(function (?string $state): ?string {
                    if (blank($state)) return null;
                    try {
                        return Carbon::parse($state)->format('Y-m-d');
                    } catch (\Exception $e) {
                        return null;
                    }
                }),
            ImportColumn::make('response')
                ->label('Response'),
            ImportColumn::make('filename')
                ->label('Filename'),
            ImportColumn::make('bank_mid')
                ->label('BankMID'),
            ImportColumn::make('bank_tid')
                ->label('BankTID'),
            ImportColumn::make('other')
                ->label('Other'),
        ];
    }

    public function resolveRecord(): ?Sti
    {
        // return Sti::where('terminal_id', trim($this->data['terminal_id']))
        //     ->where('trans_date', trim($this->data['trans_date']))
        //     ->first();
        return Sti::firstOrNew([
            'terminal_id' => $this->data['terminal_id'],
            'trans_date'  => Carbon::parse($this->data['trans_date'])
                ->format('Y-m-d H:i:s'),
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your sti import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
