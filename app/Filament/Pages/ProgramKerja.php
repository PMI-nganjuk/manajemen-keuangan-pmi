<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\ProgramKerja as ProgramKerjaModel;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Schemas\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkAction;
use Filament\Schemas\Components\Grid;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use App\Models\User;
use BackedEnum;
use UnitEnum;

class ProgramKerja extends Page implements HasForms, HasTable
{
    use Forms\Concerns\InteractsWithForms;
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $title = 'Program Kerja';
    protected static ?string $navigationLabel = 'Program Kerja';
    protected static string | UnitEnum | null $navigationGroup = 'Program & Organisasi';
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedBriefcase;
    protected string $view = 'filament.pages.program-kerja';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('nama_program')
                            ->required(),
                        Select::make('id_pegawai')
                            ->label('Nama PIC')
                            ->options(User::query()->pluck('nama', 'id_user'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Textarea::make('keterangan')->rows(3),
                    ])
                    ->columns(2),
            ])
        ->statePath('data');
    }

    public function create(): void
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $data = $this->form->getState();
            ProgramKerjaModel::create($data);

            \Illuminate\Support\Facades\DB::commit();

            Notification::make()->title('Disimpan')->success()->send();
            $this->form->fill();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Program Kerja Error: ' . $e->getMessage());

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
            ->query(ProgramKerjaModel::query()->latest())
            ->columns([
                TextColumn::make('id_program_kerja')
                    ->label('No')
                    ->sortable(),
                TextColumn::make('nama_program')
                    ->searchable(),
                TextColumn::make('pegawai.nama')
                    ->label('PIC')
                    ->sortable(),
                TextColumn::make('keterangan'),
                TextColumn::make('created_at')
                    ->searchable()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->color('warning')
                    ->modalHeading('Edit Program Kerja')
                    ->successNotificationTitle('Data berhasil diperbarui')
                    ->form([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('nama_program')
                                    ->required(),
                                Select::make('id_pegawai')
                                    ->label('Nama PIC')
                                    ->options(User::query()->pluck('nama', 'id_user'))
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Textarea::make('keterangan')->rows(3),
                            ])
                            ->columns(2),
                    ]),
                DeleteAction::make()
                    ->modalHeading('Hapus Program Kerja')
                    ->modalDescription('Apakah Anda yakin ingin menghapus data ini? Data yang dihapus tidak dapat dikembalikan.')
                    ->successNotificationTitle('Data berhasil dihapus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}