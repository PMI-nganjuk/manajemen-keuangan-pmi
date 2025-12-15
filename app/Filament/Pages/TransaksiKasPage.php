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
                    ->label('No Dokumen')
                    ->searchable(),

                Tables\Columns\TextColumn::make('referensi')
                    ->searchable(),

                Tables\Columns\TextColumn::make('rupiah')
                    ->label('Jumlah (Rp)')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('keterangan')
                    ->wrap(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),

                Tables\Columns\TextColumn::make('coa.nama_akun')
                    ->label('COA')
                    ->searchable(),

                Tables\Columns\TextColumn::make('programKerja.nama_program')
                    ->label('Program Kerja')
                    ->searchable(),

                Tables\Columns\TextColumn::make('laporanKeuangan.nama_laporan')
                    ->label('Laporan Keuangan')
                    ->searchable(),
            ])
            ->defaultPaginationPageOption(10)
            ->emptyStateHeading('Tidak ada transaksi')
            ->emptyStateDescription(
                'Data transaksi kas ditampilkan otomatis dari view sistem.'
            );
    }
}
