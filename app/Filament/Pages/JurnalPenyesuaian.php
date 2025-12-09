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
use Filament\Forms\Components\Select;

use App\Models\Penyesuaian;
use App\Models\Coa;
use App\Models\ProgramKerja;
use App\Models\LaporanKeuangan;
use UnitEnum;
use BackedEnum;

class JurnalPenyesuaian extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.jurnal-penyesuaian';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static ?string $navigationLabel = 'Jurnal Penyesuaian';
    protected static ?string $title = 'Jurnal Penyesuaian';
    protected static UnitEnum|string|null $navigationGroup = 'Keuangan';

    public ?array $data = [];
    public ?Penyesuaian $penyesuaian;

    public function mount(): void
    {
        // Ambil entri pertama (atau buat objek kosong)
        $this->penyesuaian = Penyesuaian::first() ?? new Penyesuaian();

        // Isi form
        $this->form->fill(
            $this->penyesuaian->toArray()
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Dokumen')
                    ->schema([
                        DatePicker::make('tanggal')
                            ->label('Tanggal')
                            ->required(),

                        TextInput::make('no_dokumen')
                            ->label('No Dokumen'),

                        TextInput::make('referensi')
                            ->label('Referensi'),
                    ])
                    ->columns(3),

                Section::make('Detail Transaksi')
                    ->schema([
                        TextInput::make('debit')
                            ->label('Debit')
                            ->numeric()
                            ->required()
                            ->minValue(0),

                        TextInput::make('kredit')
                            ->label('Kredit')
                            ->numeric()
                            ->required()
                            ->minValue(0),

                        TextInput::make('saldo_awal')
                            ->label('Saldo Awal')
                            ->numeric()
                            ->required()
                            ->minValue(0),

                        TextInput::make('keterangan')
                            ->label('Keterangan'),
                    ])
                    ->columns(4),

                Section::make('Relasi Sistem')
                    ->schema([
                        Select::make('id_coa')
                            ->label('COA')
                            ->options(Coa::pluck('nama_coa', 'id_coa'))
                            ->searchable()
                            ->required(),

                        Select::make('id_program_kerja')
                            ->label('Program Kerja')
                            ->options(ProgramKerja::pluck('nama_program', 'id_program_kerja'))
                            ->searchable()
                            ->required(),

                        Select::make('id_laporan')
                            ->label('Laporan Keuangan (opsional)')
                            ->options(LaporanKeuangan::pluck('nama_laporan', 'id_laporan'))
                            ->searchable(),
                    ])
                    ->columns(3),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $validated = $this->form->getState();

        if (!$this->penyesuaian->exists) {
            $this->penyesuaian = Penyesuaian::create($validated);
        } else {
            $this->penyesuaian->update($validated);
        }

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Jurnal Penyesuaian berhasil disimpan!'
        );
    }
}
