<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;
use UnitEnum;
use Filament\Support\Icons\Heroicon;

class TransaksiKast extends Page
{
    protected string $view = 'filament.pages.transaksi-kast';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;
    protected static ?string $navigationLabel = 'Transaksi Kas';
    protected static ?string $title = 'Transaksi Kas';
    protected static UnitEnum|string|null $navigationGroup = 'Keuangan';
}
