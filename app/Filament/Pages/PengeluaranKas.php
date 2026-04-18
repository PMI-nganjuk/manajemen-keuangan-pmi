<?php

namespace App\Filament\Pages;

use filament\Notifications\Notification;
use App\Models\PengeluaranKas as PengeluaranKasModel;
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
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use UnitEnum;
use BackedEnum;

class PengeluaranKas extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected string $view = 'filament.pages.pengeluaran-kas';

    protected static ?string $slug = 'pengeluaran-kas';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowUpTray;
    protected static ?string $navigationLabel = 'Pengeluaran Kas';
    protected static ?string $title = 'Pengeluaran Kas';
    protected static string|UnitEnum|null $navigationGroup = 'Keuangan';

    public array $formData = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Form Pengeluaran Kas')
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
                            ->minValue(1)
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

                        Select::make('id_laporan')
                            ->label('Laporan Keuangan')
                            ->relationship('laporanKeuangan', 'periode')
                            ->searchable()
                            ->required(),
                    ])
                    ->columns(3),
            ])
            ->statePath('formData');
    }

    public function createRecord(): void
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            PengeluaranKasModel::create($this->formData);
            
            \Illuminate\Support\Facades\DB::commit();

            $this->reset('formData');
            $this->form->fill();

            Notification::make()
                ->title('Data kas keluar berhasil ditambahkan!')
                ->success()
                ->send();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Pengeluaran Kas Error: ' . $e->getMessage());

            Notification::make()
                ->title('Gagal menyimpan data!')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(PengeluaranKasModel::query())
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
