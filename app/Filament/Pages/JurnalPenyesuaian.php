<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use App\Models\Penyesuaian;
use App\Models\Coa;
use App\Models\ProgramKerja;
use App\Models\LaporanKeuangan;
use UnitEnum;
use BackedEnum;

class JurnalPenyesuaian extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected string $view = 'filament.pages.jurnal-penyesuaian';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static ?string $navigationLabel = 'Jurnal Penyesuaian';
    protected static ?string $title = 'Jurnal Penyesuaian';
    protected static UnitEnum|string|null $navigationGroup = 'Keuangan';

    public array $formData = [];

    public function mount(): void
    {
        $this->form->fill();
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
                            ->numeric()
                            ->required()
                            ->minValue(0),

                        TextInput::make('kredit')
                            ->numeric()
                            ->required()
                            ->minValue(0),

                        TextInput::make('saldo_awal')
                            ->numeric()
                            ->required()
                            ->minValue(0),

                        TextInput::make('keterangan'),
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
                            ->label('Laporan Keuangan')
                            ->options(LaporanKeuangan::pluck('nama_laporan', 'id_laporan'))
                            ->searchable()
                            ->nullable(),
                    ])
                    ->columns(3),
            ])
            ->statePath('formData');
    }

    public function createRecord(): void
    {
        Penyesuaian::create($this->formData);

        $this->reset('formData');
        $this->form->fill();

        $this->notify('success', 'Jurnal Penyesuaian berhasil ditambahkan!');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Penyesuaian::query())
            ->columns([
                TextColumn::make('tanggal')->date(),
                TextColumn::make('no_dokumen')->searchable(),
                TextColumn::make('referensi')->searchable(),
                TextColumn::make('debit')->numeric()->sortable(),
                TextColumn::make('kredit')->numeric()->sortable(),
                TextColumn::make('keterangan')->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
