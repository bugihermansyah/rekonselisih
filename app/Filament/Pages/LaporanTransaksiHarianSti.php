<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Sti;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Enums\FiltersLayout;

class LaporanTransaksiHarianSti extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $title = 'Laporan Transaksi Harian STI';

    protected static ?string $navigationLabel = 'Laporan Harian STI';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;

    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.laporan-transaksi-harian-sti';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Sti::query()
                    ->select([
                        DB::raw("(DATE(trans_date) || '-' || COALESCE(terminal, '')) as row_key"),
                        DB::raw('DATE(trans_date) as tanggal'),
                        'terminal',
                        DB::raw('SUM(amount) as total_amount'),
                        DB::raw('COUNT(*) as total_transaksi'),
                        DB::raw("SUM(CASE WHEN response = 'VALID' THEN 1 ELSE 0 END) as total_valid"),
                        DB::raw("SUM(CASE WHEN response IS NULL OR response = '' THEN 1 ELSE 0 END) as total_null"),
                    ])
                    ->groupBy(DB::raw('DATE(trans_date)'), 'terminal')
            )
            ->columns([
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('terminal')
                    ->label('Terminal')
                    ->searchable(),
                TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: ',',
                        thousandsSeparator: '.',
                    )
                    ->prefix('Rp ')
                    ->sortable(),
                TextColumn::make('total_transaksi')
                    ->label('Total Transaksi')
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: '.',
                    )
                    ->sortable(),
                TextColumn::make('total_valid')
                    ->label('Total VALID')
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: '.',
                    )
                    ->sortable()
                    ->color('success'),
                TextColumn::make('total_null')
                    ->label('Total NULL')
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: '.',
                    )
                    ->sortable()
                    ->color('danger'),
            ])
            ->filters([
                SelectFilter::make('terminal')
                    ->label('Terminal')
                    ->options(fn() => Sti::query()->distinct()->whereNotNull('terminal')->pluck('terminal', 'terminal')->toArray())
                    ->searchable(),
                Filter::make('trans_date_from')
                    ->schema([
                        DatePicker::make('from')
                            ->label('Dari Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('trans_date', '>=', $date),
                            );
                    }),
                Filter::make('trans_date_until')
                    ->schema([
                        DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('trans_date', '<=', $date),
                            );
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->defaultSort('tanggal', 'desc')
            ->persistFiltersInSession();
    }

    public function getTableRecordKey(Model | array $record): string
    {
        if ($record instanceof Model) {
            return $record->getAttribute('row_key') ?? '';
        }

        return $record['row_key'] ?? '';
    }
}
