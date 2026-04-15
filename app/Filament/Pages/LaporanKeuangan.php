<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema as SchemaFacade;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use BackedEnum;

class LaporanKeuangan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Laporan Keuangan';
    protected static ?string $title = 'Laporan Keuangan';
    protected string $view = 'filament.pages.laporan-keuangan';
    protected static UnitEnum|string|null $navigationGroup = 'Laporan';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocument;

    public ?int $tahun = null;
    public ?array $data = [];
    public array $laporan = [];

    public function mount(): void
    {
        $this->tahun = (int) date('Y');
        $this->form->fill(['tahun' => $this->tahun]);
        $this->loadData();
    }

    public function loadData(): void
    {
        if (! SchemaFacade::hasTable('laporan_keuangan')) {
            $this->laporan = [];

            return;
        }

        $query = DB::table('laporan_keuangan');

        if (SchemaFacade::hasColumn('laporan_keuangan', 'tahun') && $this->tahun !== null) {
            $query->where('tahun', $this->tahun);
        }

        $this->laporan = $query->get()->toArray();
    }

    public function downloadPdf()
    {
        return redirect()->route('laporan-keuangan.pdf', [
            'tahun' => $this->tahun,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('tahun')
                    ->label('Tahun Laporan')
                    ->numeric()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state): void {
                        $this->tahun = is_numeric($state) ? (int) $state : null;
                        $this->loadData();
                    }),
            ])
            ->statePath('data');
    }
}
