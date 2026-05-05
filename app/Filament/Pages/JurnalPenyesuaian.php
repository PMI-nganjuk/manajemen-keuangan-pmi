<?php

namespace App\Filament\Pages;

use App\Models\Penyesuaian;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use UnitEnum;
use BackedEnum;
use Illuminate\Validation\ValidationException;

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
    public ?int $editingId = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->model(Penyesuaian::class)
            ->statePath('formData')
            ->schema([
                DatePicker::make('tanggal')
                    ->label('Tanggal Penyesuaian')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y'),

                TextInput::make('no_dokumen')
                    ->label('No. Dokumen')
                    ->nullable()
                    ->maxLength(100),

                TextInput::make('referensi')
                    ->label('Referensi')
                    ->nullable()
                    ->maxLength(100),

                TextInput::make('debit')
                    ->label('Debit')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),

                TextInput::make('kredit')
                    ->label('Kredit')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),

                TextInput::make('saldo_awal')
                    ->label('Saldo Awal')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),

                Select::make('id_coa')
                    ->label('Chart of Accounts')
                    ->relationship('coa', 'account_name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('id_program_kerja')
                    ->label('Program Kerja')
                    ->relationship('programKerja', 'nama_program')
                    ->searchable()
                    ->preload()
                    ->required(),

                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->nullable()
                    ->maxLength(500)
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }

    public function createRecord(): void
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Dapatkan data yang sudah tervalidasi langsung dari form Filament
            $data = $this->form->getState();

            if ($this->editingId) {
                // Update existing record
                $record = Penyesuaian::findOrFail($this->editingId);
                $record->update($data);

                Notification::make()
                    ->title('Data Penyesuaian berhasil diperbarui!')
                    ->success()
                    ->send();

                $this->editingId = null;
            } else {
                // Create new record
                Penyesuaian::create($data);

                Notification::make()
                    ->title('Data Penyesuaian berhasil ditambahkan!')
                    ->success()
                    ->send();
            }

            \Illuminate\Support\Facades\DB::commit();
            $this->resetForm();
            $this->dispatch('refresh');
        } catch (ValidationException $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            Notification::make()
                ->title('Validasi gagal!')
                ->body($e->getMessage())
                ->danger()
                ->send();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Jurnal Penyesuaian Error: ' . $e->getMessage());

            Notification::make()
                ->title('Gagal menyimpan data!')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }


    public function resetForm(): void
    {
        $this->formData = [];
        $this->editingId = null;
        $this->form->fill();
    }

    public function editRecord(int $id): void
    {
        try {
            $record = Penyesuaian::findOrFail($id);
            $this->editingId = $id;

            // Fix form state
            $this->form->fill([
                'tanggal' => $record->tanggal,
                'no_dokumen' => $record->no_dokumen,
                'referensi' => $record->referensi,
                'debit' => $record->debit,
                'kredit' => $record->kredit,
                'saldo_awal' => $record->saldo_awal,
                'id_coa' => $record->id_coa,
                'id_program_kerja' => $record->id_program_kerja,
                'keterangan' => $record->keterangan,
            ]);

            Notification::make()
                ->title('Mode Edit - Data siap diperbarui')
                ->info()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal memuat data!')
                ->body('Data tidak ditemukan')
                ->danger()
                ->send();
        }
    }

    public function deleteRecord(int $id): void
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $record = Penyesuaian::findOrFail($id);
            $record->delete();

            \Illuminate\Support\Facades\DB::commit();

            Notification::make()
                ->title('Data Penyesuaian berhasil dihapus!')
                ->success()
                ->send();

            $this->dispatch('refresh');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Jurnal Penyesuaian Delete Error: ' . $e->getMessage());

            Notification::make()
                ->title('Gagal menghapus data!')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Penyesuaian::query())
            ->defaultSort('tanggal', 'desc')
            ->columns([
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('no_dokumen')
                    ->label('No. Dokumen')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('referensi')
                    ->label('Referensi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('coa.account_name')
                    ->label('COA')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('programKerja.nama_program')
                    ->label('Program Kerja')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('debit')
                    ->label('Debit')
                    ->numeric(locale: 'id')
                    ->sortable(),

                TextColumn::make('kredit')
                    ->label('Kredit')
                    ->numeric(locale: 'id')
                    ->sortable(),

                TextColumn::make('saldo_awal')
                    ->label('Saldo Awal')
                    ->numeric(locale: 'id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->color('warning')
                    ->action(function (Penyesuaian $record) {
                        $this->editRecord($record->id_penyesuaian);
                    }),
                DeleteAction::make()
                    ->action(function (Penyesuaian $record) {
                        $this->deleteRecord($record->id_penyesuaian);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}