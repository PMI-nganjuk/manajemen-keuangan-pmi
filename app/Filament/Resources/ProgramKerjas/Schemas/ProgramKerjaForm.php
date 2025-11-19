<?php

namespace App\Filament\Resources\ProgramKerjas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProgramKerjaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_program')
                    ->required(),
                TextInput::make('keterangan')
                    ->default(null),
                TextInput::make('id_pegawai')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
