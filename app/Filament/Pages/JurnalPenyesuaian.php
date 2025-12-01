<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class JurnalPenyesuaian extends Page
{
    protected string $view = 'filament.pages.jurnal-penyesuaian';

   protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static ?string $navigationLabel = 'Jurnal Penyesuaian';
    protected static ?string $title = 'Jurnal Penyesuaian';
    static UnitEnum|string|null $navigationGroup = 'Keuangan';
}
