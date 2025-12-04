<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use BackedEnum;

class LaporanKeuangan extends Page
{
    protected static ?string $navigationLabel = 'Laporan Keuangan';
    protected static ?string $title = 'Laporan Keuangan';
    protected string $view = 'filament.pages.laporan-keuangan';
    protected static string | UnitEnum | null $navigationGroup = 'Laporan';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocument;

    public ?int $tahun = null;
    public array $laporan = [];

    public function mount()
    {
        $this->tahun = date('Y');
        $this->loadData();
    }

    public function updatedTahun()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Contoh query (silakan sesuaikan)
        $this->laporan = DB::table('laporan_keuangan')
            ->whereYear('tanggal', $this->tahun)
            ->get()
            ->toArray();
    }

    public function downloadPdf()
    {
        return redirect()->route('laporan-keuangan.pdf', [
            'tahun' => $this->tahun,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('tahun')
                ->label('Tahun Laporan')
                ->numeric()
                ->required()
                ->reactive(),
        ];
    }
}
