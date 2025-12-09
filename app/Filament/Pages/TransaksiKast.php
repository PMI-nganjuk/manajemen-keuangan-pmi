<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Support\Icons\Heroicon;

use App\Models\TransaksiKas;
use App\Filament\Resources\TransaksiKas\Schemas\TransaksiKasForm;

use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;

class TransaksiKast extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected string $view = 'filament.pages.transaksi-kast';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;
    protected static ?string $navigationLabel = 'Transaksi Kas';
    protected static ?string $title = 'Transaksi Kas';
    protected static UnitEnum|string|null $navigationGroup = 'Keuangan';

    public ?array $data = [];
    public ?TransaksiKas $transaksi;

    public function mount(): void
    {
        $this->transaksi = TransaksiKas::first() ?? new TransaksiKas();

        $this->form->fill(
            $this->transaksi->toArray()
        );
    }

    public function form(Schema $schema): Schema
    {
        return TransaksiKasForm::configure($schema)
            ->statePath('data');
    }

    public function save(): void
    {
        $this->dispatch(
            'notify',
            type: 'warning',
            message: 'Data Transaksi Kas berasal dari VIEW dan tidak dapat disimpan.'
        );
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                TransaksiKas::query()
            )
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->date()->sortable(),
                Tables\Columns\TextColumn::make('no_dokumen')->searchable(),
                Tables\Columns\TextColumn::make('referensi')->searchable(),
                Tables\Columns\TextColumn::make('rupiah')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('keterangan'),
                Tables\Columns\TextColumn::make('id_user'),
                Tables\Columns\TextColumn::make('id_coa'),
                Tables\Columns\TextColumn::make('id_program_kerja'),
                Tables\Columns\TextColumn::make('id_laporan_keuangan'),
            ])
            ->defaultPaginationPageOption(10);
    }
}
