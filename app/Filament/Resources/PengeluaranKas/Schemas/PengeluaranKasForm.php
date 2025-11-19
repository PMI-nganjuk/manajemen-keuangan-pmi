<?php

namespace App\Filament\Resources\PengeluaranKas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PengeluaranKasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('tanggal')
                    ->required(),
                TextInput::make('no_dokumen')
                    ->default(null),
                TextInput::make('referensi')
                    ->default(null),
                TextInput::make('rupiah')
                    ->required()
                    ->numeric(),
                TextInput::make('keterangan')
                    ->default(null),
                TextInput::make('id_user')
                    ->required()
                    ->numeric(),
                TextInput::make('id_coa')
                    ->required()
                    ->numeric(),
                TextInput::make('id_program_kerja')
                    ->required()
                    ->numeric(),
                TextInput::make('id_laporan')
                    ->required()
                    ->numeric(),
            ]);
    }
}
