<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use App\Models\PengeluaranKas as PengeluaranKasModel;
use UnitEnum;
use BackedEnum;

class PengeluaranKast extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.penerimaan-kas';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;
    protected static ?string $navigationLabel = 'Penerimaan Kas';
    protected static ?string $title = 'Penerimaan Kas';
    protected static UnitEnum|string|null $navigationGroup = 'Keuangan';

    public ?array $data = [];
    public ?PengeluaranKasModel $kas;

    public function mount(): void
    {
        // Ambil data pertama, atau buat objek kosong
        $this->kas = PengeluaranKasModel::first() ?? new PengeluaranKasModel();

        // Isi form state
        $this->form->fill(
            $this->kas->toArray()
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Dokumen')
                    ->schema([
                        DatePicker::make('tanggal')->label('Tanggal')->required(),
                        TextInput::make('no_dokumen')->label('No Dokumen'),
                        TextInput::make('referensi')->label('Referensi'),
                    ])
                    ->columns(3),

                Section::make('Detail Transaksi')
                    ->schema([
                        TextInput::make('rupiah')
                            ->label('Jumlah (Rupiah)')
                            ->required()
                            ->numeric(),

                        TextInput::make('keterangan')
                            ->label('Keterangan'),
                    ])
                    ->columns(2),

                Section::make('Relasi Sistem')
                    ->schema([
                        TextInput::make('id_user')
                            ->label('ID User')
                            ->required()
                            ->numeric(),

                        TextInput::make('id_coa')
                            ->label('ID COA')
                            ->required()
                            ->numeric(),

                        TextInput::make('id_program_kerja')
                            ->label('ID Program Kerja')
                            ->required()
                            ->numeric(),

                        TextInput::make('id_laporan_keuangan')
                            ->label('ID Laporan Keuangan')
                            ->required()
                            ->numeric(),
                    ])
                    ->columns(4),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $validated = $this->form->getState();

        if (!$this->kas->exists) {
            $this->kas = PengeluaranKasModel::create($validated);
        } else {
            $this->kas->update($validated);
        }

        $this->dispatch('notify', type: 'success', message: 'Data Penerimaan Kas berhasil disimpan!');
    }
}
