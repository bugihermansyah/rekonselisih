<?php

namespace App\Filament\Resources\Stis\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('card_type'),
                TextInput::make('terminal_id'),
                TextInput::make('terminal'),
                TextInput::make('card_no'),
                TextInput::make('amount')
                    ->numeric(),
                TextInput::make('balance')
                    ->numeric(),
                DateTimePicker::make('trans_date'),
                TextInput::make('ftp_file'),
                DatePicker::make('created_date'),
                DatePicker::make('settle_date'),
                TextInput::make('response'),
                TextInput::make('filename'),
                TextInput::make('bank_mid'),
                TextInput::make('bank_tid'),
                Textarea::make('other')
                    ->columnSpanFull(),
            ]);
    }
}
