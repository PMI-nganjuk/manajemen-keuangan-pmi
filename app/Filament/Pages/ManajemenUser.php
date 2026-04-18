<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
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
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkAction;
use Filament\Schemas\Components\Grid;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use App\Models\User;
use BackedEnum;
use UnitEnum;

class ManajemenUser extends Page implements HasForms, HasTable
{
    use Forms\Concerns\InteractsWithForms;
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $title = 'Daftar Pegawai';
    protected static ?string $navigationLabel = 'Daftar Pegawai';
    protected static string | UnitEnum | null $navigationGroup = 'Program & Organisasi';
    protected static string | BackedEnum |null $navigationIcon = Heroicon::OutlinedUsers;
    protected string $view = 'filament.pages.manajemen-user';

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
                        Select::make('kategori')
                    ->options([
            'karyawan' => 'Karyawan',
            'donatur' => 'Donatur',
            'kreditur' => 'Kreditur',
            'debitur' => 'Debitur',
        ])
                    ->default(null),
                TextInput::make('nama')
                    ->default(null),
                TextInput::make('nomer_wa')
                    ->default(null),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('alamat')
                    ->default(null),
                Select::make('role')
                    ->options([
            'admin' => 'Admin',
            'manager_keuangan' => 'Manager keuangan',
            'staf_keuangan' => 'Staf keuangan',
            'pegawai' => 'Pegawai',
        ])
                    ->default('pegawai')
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(),
                    ])
                    ->columns(2),
            ])     
        ->statePath('data');
    }

    public function create(): void
    {
        $data = $this->form->getState();
        User::create($data);
        Notification::make()->title('Disimpan')->success()->send();
        $this->form->fill();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->latest())
            ->columns([
                TextColumn::make('kategori'),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('nomer_wa')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('alamat')
                    ->searchable(),
                TextColumn::make('role'),
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
                EditAction::make()->color('warning'),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
