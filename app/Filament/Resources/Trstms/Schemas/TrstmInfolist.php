<?php

namespace App\Filament\Resources\Trstms\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TrstmInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('trs_id')
                    ->placeholder('-'),
                TextEntry::make('trstm_date')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('emoney_type')
                    ->placeholder('-'),
                TextEntry::make('card_no')
                    ->placeholder('-'),
                TextEntry::make('mid')
                    ->placeholder('-'),
                TextEntry::make('terminal_id')
                    ->placeholder('-'),
                TextEntry::make('amount')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('balance')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('log')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('inv')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('counter')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('bank_type')
                    ->placeholder('-'),
                TextEntry::make('user_id')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
