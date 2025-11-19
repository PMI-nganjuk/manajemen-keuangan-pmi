<?php

namespace App\Filament\Resources\PenerimaanKas;

use App\Filament\Resources\PenerimaanKas\Pages\CreatePenerimaanKas;
use App\Filament\Resources\PenerimaanKas\Pages\EditPenerimaanKas;
use App\Filament\Resources\PenerimaanKas\Pages\ListPenerimaanKas;
use App\Filament\Resources\PenerimaanKas\Schemas\PenerimaanKasForm;
use App\Filament\Resources\PenerimaanKas\Tables\PenerimaanKasTable;
use App\Models\PenerimaanKas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PenerimaanKasResource extends Resource
{
    protected static ?string $model = PenerimaanKas::class;

    protected static string | UnitEnum | null $navigationGroup = 'Keuangan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowDownTray;

    public static function form(Schema $schema): Schema
    {
        return PenerimaanKasForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PenerimaanKasTable::configure($table);
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
            'index' => ListPenerimaanKas::route('/'),
            'create' => CreatePenerimaanKas::route('/create'),
            'edit' => EditPenerimaanKas::route('/{record}/edit'),
        ];
    }
}
