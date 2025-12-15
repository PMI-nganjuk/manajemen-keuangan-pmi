<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Coa as CoaModel;
use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use UnitEnum;
use BackedEnum;

class Coa extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected string $view = 'filament.pages.coa';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArchiveBox;
    protected static ?string $navigationLabel = 'Chart of Accounts (COA)';
    protected static ?string $title = 'Chart of Accounts (COA)';
    protected static UnitEnum|string|null $navigationGroup = 'Keuangan';

    public array $formData = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    // ================= FORM =================
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Data Akun')
                    ->schema([
                        TextInput::make('id_coa')
                            ->label('Kode Akun')
                            ->required()
                            ->maxLength(10)
                            ->unique(
                                table: CoaModel::class,
                                column: 'id_coa',
                                ignoreRecord: true
                            ),

                        TextInput::make('nama_akun')
                            ->label('Nama Akun')
                            ->required(),

                        TextInput::make('pos_saldo')
                            ->label('Pos Saldo')
                            ->required(),

                        TextInput::make('pos_laporan')
                            ->label('Pos Laporan')
                            ->required(),
                    ])
                    ->columns(2),
            ])
            ->statePath('formData');
    }

    // ================= CREATE =================
    public function createRecord(): void
    {
        CoaModel::create($this->formData);

        $this->reset('formData');
        $this->form->fill();

        $this->notify('success', 'Data COA berhasil ditambahkan!');
    }

    // ================= TABLE =================
    public function table(Table $table): Table
    {
        return $table
            ->query(CoaModel::query())
            ->columns([
                TextColumn::make('id_coa')
                    ->label('Kode Akun')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_akun')
                    ->searchable(),

                TextColumn::make('pos_saldo')
                    ->searchable(),

                TextColumn::make('pos_laporan')
                    ->searchable(),

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
