<?php

namespace App\Filament\Resources\TransaksiKas;

use App\Filament\Resources\TransaksiKas\Pages\CreateTransaksiKas;
use App\Filament\Resources\TransaksiKas\Pages\EditTransaksiKas;
use App\Filament\Resources\TransaksiKas\Pages\ListTransaksiKas;
use App\Filament\Resources\TransaksiKas\Schemas\TransaksiKasForm;
use App\Filament\Resources\TransaksiKas\Tables\TransaksiKasTable;
use App\Models\TransaksiKas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TransaksiKasResource extends Resource
{
    protected static ?string $model = TransaksiKas::class;

    protected static string | UnitEnum | null $navigationGroup = 'Keuangan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TransaksiKasForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransaksiKasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTransaksiKas::route('/'),
            'create' => CreateTransaksiKas::route('/create'),
            'edit' => EditTransaksiKas::route('/{record}/edit'),
        ];
    }
}
