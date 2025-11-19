<?php

namespace App\Filament\Pages;

use App\Models\ProfilPmi as ProfilPmiModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use BackedEnum;
use UnitEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Section;

class ProfilPmi extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.profil-pmi';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;
    protected static ?string $navigationLabel = 'Profil Organisasi';
    protected static ?string $title = 'Profil Organisasi';
    protected static string | UnitEnum | null $navigationGroup = 'Program & Organisasi';

    public ?array $data = [];
    public ?ProfilPmiModel $profil;

    public function mount(): void
    {
        // Ambil profil pertama, atau buat baru jika tidak ada
        $this->profil = ProfilPmiModel::first() ?? ProfilPmiModel::create([]);

        // Isi form dengan data model
        $this->form->fill(
            $this->profil->toArray()
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Umum')
                    ->schema([
                        TextInput::make('nama_pmi')
                            ->label('Nama PMI'),

                        Textarea::make('alamat')
                            ->label('Alamat')
                            ->rows(3),
                    ])
                    ->columns(2),

                Section::make('Pengurus')
                    ->schema([
                        TextInput::make('ketua')->label('Ketua'),
                        TextInput::make('kepala_markas')->label('Kepala Markas'),
                        TextInput::make('kepala_uud')->label('Kepala UUD'),
                        TextInput::make('bendahara_markas')->label('Bendahara Markas'),
                        TextInput::make('bendahara_uud')->label('Bendahara UUD'),
                    ])
                    ->columns(2),

                Section::make('Periode Buku')
                    ->schema([
                        DatePicker::make('periode_buku_awal')->label('Periode Awal'),
                        DatePicker::make('periode_buku_akhir')->label('Periode Akhir'),
                        TextInput::make('tahun_buku')->label('Tahun Buku')->type('number'),
                    ])
                    ->columns(3),
            ])
            ->statePath('data');
    }

    public function update(): void
    {
        $this->profil->update($this->data);

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Profil PMI berhasil diperbarui!'
        );
    }
}