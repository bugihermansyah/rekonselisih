<?php

namespace App\Filament\Resources\Stis\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('card_type')
                    ->placeholder('-'),
                TextEntry::make('terminal_id')
                    ->placeholder('-'),
                TextEntry::make('terminal')
                    ->placeholder('-'),
                TextEntry::make('card_no')
                    ->placeholder('-'),
                TextEntry::make('amount')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('balance')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('trans_date')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('ftp_file')
                    ->placeholder('-'),
                TextEntry::make('created_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('settle_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('response')
                    ->placeholder('-'),
                TextEntry::make('filename')
                    ->placeholder('-'),
                TextEntry::make('bank_mid')
                    ->placeholder('-'),
                TextEntry::make('bank_tid')
                    ->placeholder('-'),
                TextEntry::make('other')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
