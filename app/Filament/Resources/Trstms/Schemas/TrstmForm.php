<?php

namespace App\Filament\Resources\Trstms\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TrstmForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('trs_id'),
                DateTimePicker::make('trstm_date'),
                TextInput::make('emoney_type'),
                TextInput::make('card_no'),
                TextInput::make('mid'),
                TextInput::make('terminal_id'),
                TextInput::make('amount')
                    ->numeric(),
                TextInput::make('balance')
                    ->numeric(),
                Textarea::make('log')
                    ->columnSpanFull(),
                Textarea::make('inv')
                    ->columnSpanFull(),
                TextInput::make('counter')
                    ->numeric(),
                TextInput::make('bank_type'),
                TextInput::make('user_id'),
            ]);
    }
}
