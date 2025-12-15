<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\PenerimaanKas as PenerimaanKasModel;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use UnitEnum;

class PenerimaanKas extends Page implements HasForms, HasTable
{
    use Forms\Concerns\InteractsWithForms;
    use Tables\Concerns\InteractsWithTable;

    protected string $view = 'filament.pages.penerimaan-kas';
    protected static ?string $navigationLabel = 'Penerimaan Kas';
    protected static ?string $title = 'Penerimaan Kas';
    protected static string | UnitEnum | null $navigationGroup = 'Keuangan';

    public $formData = [];

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
                        TextInput::make('rupiah')
                            ->label('Jumlah (Rupiah)')
                            ->numeric()
                            ->required(),

                        TextInput::make('keterangan')
                            ->label('Keterangan'),
                    ])
                    ->columns(2),

                Section::make('Relasi Sistem')
                    ->schema([
                        TextInput::make('id_user')
                            ->numeric()
                            ->required(),

                        TextInput::make('id_coa')
                            ->numeric()
                            ->required(),

                        TextInput::make('id_program_kerja')
                            ->numeric()
                            ->required(),

                        TextInput::make('id_laporan_keuangan')
                            ->numeric()
                            ->required(),
                    ])
                    ->columns(4),
            ])
            ->statePath('formData');
    }

    public function createRecord(): void
    {
        PenerimaanKasModel::create($this->formData);

        $this->reset('formData');
        $this->form->fill();

        $this->notify('success', 'Data Penerimaan Kas berhasil ditambahkan!');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(PenerimaanKasModel::query())
            ->columns([
                TextColumn::make('tanggal')->date(),
                TextColumn::make('no_dokumen')->searchable(),
                TextColumn::make('referensi')->searchable(),
                TextColumn::make('rupiah')->numeric()->sortable(),
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
