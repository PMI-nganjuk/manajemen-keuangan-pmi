<?php

namespace App\Filament\Resources\PengeluaranKas;

use App\Filament\Resources\PengeluaranKas\Pages\CreatePengeluaranKas;
use App\Filament\Resources\PengeluaranKas\Pages\EditPengeluaranKas;
use App\Filament\Resources\PengeluaranKas\Pages\ListPengeluaranKas;
use App\Filament\Resources\PengeluaranKas\Schemas\PengeluaranKasForm;
use App\Filament\Resources\PengeluaranKas\Tables\PengeluaranKasTable;
use App\Models\PengeluaranKas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PengeluaranKasResource extends Resource
{
    protected static ?string $model = PengeluaranKas::class;

    protected static string | UnitEnum | null $navigationGroup = 'Keuangan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUpTray;

    public static function form(Schema $schema): Schema
    {
        return PengeluaranKasForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengeluaranKasTable::configure($table);
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
            'index' => ListPengeluaranKas::route('/'),
            'create' => CreatePengeluaranKas::route('/create'),
            'edit' => EditPengeluaranKas::route('/{record}/edit'),
        ];
    }
}
