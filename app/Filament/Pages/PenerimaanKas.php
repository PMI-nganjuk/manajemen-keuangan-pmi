<?php

namespace App\Filament\Pages;

use Filament\Support\Icons\Heroicon;
use App\Models\PenerimaanKas as PenerimaanKasModel;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use UnitEnum;
use BackedEnum;

class PenerimaanKas extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected string $view = 'filament.pages.penerimaan-kas';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowDownTray;

    protected static ?string $navigationLabel = 'Penerimaan Kas';
    protected static ?string $title = 'Penerimaan Kas';
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
                Section::make('Form Penerimaan Kas')
                    ->schema([
                        DatePicker::make('tanggal')
                            ->required(),

                        TextInput::make('no_dokumen')
                            ->nullable(),

                        TextInput::make('referensi')
                            ->nullable(),

                        TextInput::make('rupiah')
                            ->label('Jumlah (Rupiah)')
                            ->numeric()
                            ->minValue(0)
                            ->required(),

                        TextInput::make('keterangan')
                            ->nullable(),

                        Select::make('id_user')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),

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

                        Select::make('id_laporan_keuangan')
                            ->label('Laporan Keuangan')
                            ->relationship('laporanKeuangan', 'nama_laporan')
                            ->searchable()
                            ->required(),
                    ])
                    ->columns(3),
            ])
            ->statePath('formData');
    }

    public function createRecord(): void
    {
        PenerimaanKasModel::create($this->formData);

        $this->reset('formData');
        $this->form->fill();

        Notification::make()
            ->title('Data kas masuk berhasil ditambahkan!')
            ->success()
            ->send();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(PenerimaanKasModel::query())
            ->columns([
                TextColumn::make('tanggal')->date(),

                TextColumn::make('no_dokumen')->searchable(),

                TextColumn::make('referensi')->searchable(),

                TextColumn::make('coa.nama_akun')
                    ->label('COA')
                    ->searchable(),

                TextColumn::make('programKerja.nama_program')
                    ->label('Program Kerja')
                    ->searchable(),

                TextColumn::make('rupiah')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make()->color('warning'),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
