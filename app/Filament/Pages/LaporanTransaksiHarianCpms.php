<?php

namespace App\Filament\Pages;

use App\Models\Trstm;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LaporanTransaksiHarianCpms extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $title = 'Laporan Transaksi Harian CPMS';

    protected static ?string $navigationLabel = 'Laporan Harian CPMS';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;

    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.laporan-transaksi-harian-cpms';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Trstm::query()
                    ->select([
                        DB::raw("(DATE(trstm_date) || '-' || COALESCE(terminal_id, '')) as row_key"),
                        DB::raw('DATE(trstm_date) as tanggal'),
                        'terminal_id',
                        DB::raw('SUM(amount) as total_amount'),
                        DB::raw('COUNT(*) as total_transaksi'),
                    ])
                    ->groupBy(DB::raw('DATE(trstm_date)'), 'terminal_id')
            )
            ->columns([
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('terminal_id')
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
            ])
            ->filters([
                SelectFilter::make('terminal_id')
                    ->label('Terminal')
                    ->options(fn() => Trstm::query()->distinct()->whereNotNull('terminal_id')->pluck('terminal_id', 'terminal_id')->toArray())
                    ->searchable(),
                Filter::make('trstm_date_from')
                    ->schema([
                        DatePicker::make('from')
                            ->label('Dari Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('trstm_date', '>=', $date),
                            );
                    }),
                Filter::make('trstm_date_until')
                    ->schema([
                        DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('trstm_date', '<=', $date),
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
