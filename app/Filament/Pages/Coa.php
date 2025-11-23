<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Coa as CoaModel;
use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Support\Icons\Heroicon;


class Coa extends Page implements HasForms
{
    use InteractsWithForms;

    // protected string $view = 'filament.pages.coa';
    // protected static string|BackedEnum|null $navigationIcon = Heroicon::ArchiveBox;
    // protected static ?string $navigationLabel = 'Chart of Accounts (COA)';
    // protected static ?string $title = 'Chart of Accounts (COA)';
    // protected static UnitEnum|string|null $navigationGroup = 'Keuangan';


    public ?array $data = [];
    public ?CoaModel $coa;

    public function mount(): void
    {
        // Ambil entry pertama, atau buat baru jika tabel masih kosong
        $this->coa = CoaModel::first() ?? new CoaModel();

        // Isi state form
        $this->form->fill(
            $this->coa->toArray()
        );
    }
    // FORM SCHEMA
    public function form($form)
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_coa')
                    ->label('Kode Akun')
                    ->required()
                    ->maxLength(10)
                    ->unique(ignoreRecord: true),
    
                Forms\Components\TextInput::make('nama_akun')
                    ->label('Nama Akun')
                    ->required()
                    ->maxLength(255),
    
                Forms\Components\TextInput::make('pos_saldo')
                    ->label('Pos Saldo')
                    ->required()
                    ->maxLength(255),
    
                Forms\Components\TextInput::make('pos_laporan')
                    ->label('Pos Laporan')
                    ->required()
                    ->maxLength(255),
            ])
            ->statePath('data');
    }
    public function save(): void
    {
        $validated = $this->form->getState();

        // Jika data belum ada → CREATE
        if (!$this->coa->exists) {
            $this->coa = CoaModel::create($validated);
        } 
        // Jika sudah ada → UPDATE
        else {
            $this->coa->update($validated);
        }

        $this->notify('success', 'Data COA berhasil disimpan.');
    }
}
