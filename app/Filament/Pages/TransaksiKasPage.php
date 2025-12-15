<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Support\Icons\Heroicon;
use App\Models\TransaksiKas;
use BackedEnum;
use UnitEnum;

class TransaksiKasPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.transaksi-kas';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;
    protected static ?string $navigationLabel = 'Transaksi Kas';
    protected static ?string $title = 'Transaksi Kas';
    protected static UnitEnum|string|null $navigationGroup = 'Keuangan';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(TransaksiKas::query())
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('no_dokumen')
                    ->searchable(),

                Tables\Columns\TextColumn::make('referensi')
                    ->searchable(),

                Tables\Columns\TextColumn::make('rupiah')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('keterangan'),

                Tables\Columns\TextColumn::make('id_user'),
                Tables\Columns\TextColumn::make('id_coa'),
                Tables\Columns\TextColumn::make('id_program_kerja'),
                Tables\Columns\TextColumn::make('id_laporan_keuangan'),
            ])
            ->defaultPaginationPageOption(10)
            ->emptyStateHeading('Tidak ada transaksi')
            ->emptyStateDescription('Data transaksi kas diambil dari view sistem.');
    }
}
