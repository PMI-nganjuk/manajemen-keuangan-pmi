<?php

namespace App\Filament\Resources\Coas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CoaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_akun')
                    ->required(),
                TextInput::make('pos_saldo')
                    ->required(),
                TextInput::make('pos_laporan')
                    ->required(),
            ]);
    }
}
