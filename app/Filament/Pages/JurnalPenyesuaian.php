<?php

namespace App\Filament\Pages;

use App\Models\Penyesuaian;
use Filament\Actions\DeleteAction;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
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
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
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
                Section::make('Form Jurnal Penyesuaian')
                    ->schema([
                        // Informasi Dokumen
                        DatePicker::make('tanggal')
                            ->label('Tanggal')
                            ->required(),

                        TextInput::make('no_dokumen')
                            ->label('No Dokumen')
                            ->nullable(),

                        TextInput::make('referensi')
                            ->label('Referensi')
                            ->nullable(),

                        // Detail Transaksi
                        TextInput::make('debit')
                            ->numeric()
                            ->minValue(0)
                            ->required(),

                        TextInput::make('kredit')
                            ->numeric()
                            ->minValue(0)
                            ->required(),

                        TextInput::make('saldo_awal')
                            ->numeric()
                            ->minValue(0)
                            ->required(),

                        TextInput::make('keterangan')
                            ->nullable(),

                        // Relasi Sistem
                        Select::make('id_coa')
                            ->label('COA')
                            ->relationship('coa', 'nama_akun')
                            ->searchable()
                            ->required(),

                        Select::make('id_program_kerja')
                            ->label('Program Kerja')
                            ->relationship('programKerja', 'nama_program')
                            ->searchable()
                            ->required(),

                        Select::make('id_laporan')
                            ->label('Laporan Keuangan')
                            ->relationship('laporan', 'nama_laporan')
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

        Notification::make()
            ->title('Data Penyesuaian berhasil ditambahkan!')
            ->success()
            ->send();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Penyesuaian::query())
            ->columns([
                TextColumn::make('tanggal')->date(),

                TextColumn::make('no_dokumen')
                    ->searchable(),

                TextColumn::make('referensi')
                    ->searchable(),

                TextColumn::make('coa.nama_akun')
                    ->label('COA')
                    ->searchable(),

                TextColumn::make('programKerja.nama_program')
                    ->label('Program Kerja')
                    ->searchable(),

                TextColumn::make('debit')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('kredit')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make()->color('warning' ),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
