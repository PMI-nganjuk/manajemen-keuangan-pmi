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
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Schemas\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions\EditAction;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Models\User;
use BackedEnum;
use UnitEnum;

class ProgramKerja extends Page implements HasForms, HasTable
{
    use Forms\Concerns\InteractsWithForms;
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationLabel = 'Program Kerja';
    protected static ?string $title = 'Program Kerja';
    protected string $view = 'filament.pages.program-kerja';
    protected static string | UnitEnum | null $navigationGroup = 'Program & Organisasi';

    public $formData = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Input Program Kerja')
                    ->schema([
                        TextInput::make('nama_program')
                            ->required()
                            ->label('Nama Program'),

                        Textarea::make('keterangan')->rows(3),

                        Select::make('id_pegawai')
                            ->label('Nama Pegawai')
                            ->relationship(name: 'pegawai', titleAttribute: 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->columns(2),
            ])     
        ->statePath('formData');
    }

    public function createRecord()
    {
        ProgramKerjaModel::create($this->formData);
        $this->reset('formData');
        $this->form->fill();

        $this->notify('success', 'Data berhasil ditambahkan!');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ProgramKerjaModel::query())
            ->columns([
                TextColumn::make('nama_program')
                    ->searchable(),
                TextColumn::make('keterangan')
                    ->searchable(),
                TextColumn::make('id_pegawai')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
