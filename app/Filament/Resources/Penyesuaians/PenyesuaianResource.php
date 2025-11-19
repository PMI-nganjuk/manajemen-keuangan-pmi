<?php

namespace App\Filament\Resources\Penyesuaians;

use App\Filament\Resources\Penyesuaians\Pages\CreatePenyesuaian;
use App\Filament\Resources\Penyesuaians\Pages\EditPenyesuaian;
use App\Filament\Resources\Penyesuaians\Pages\ListPenyesuaians;
use App\Filament\Resources\Penyesuaians\Schemas\PenyesuaianForm;
use App\Filament\Resources\Penyesuaians\Tables\PenyesuaiansTable;
use App\Models\Penyesuaian;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PenyesuaianResource extends Resource
{
    protected static ?string $model = Penyesuaian::class;

    protected static string | UnitEnum | null $navigationGroup = 'Keuangan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    public static function getNavigationLabel(): string
    {
        return 'Jurnal Penyesuaian';
    }

        public static function getPluralLabel(): string
    {
        return 'Jurnal Penyesuaian';
    }

    public static function form(Schema $schema): Schema
    {
        return PenyesuaianForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PenyesuaiansTable::configure($table);
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
            'index' => ListPenyesuaians::route('/'),
            'create' => CreatePenyesuaian::route('/create'),
            'edit' => EditPenyesuaian::route('/{record}/edit'),
        ];
    }
}
