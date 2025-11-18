<?php

namespace App\Filament\Resources\TransaksiKas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransaksiKasForm
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
                    ->numeric()
                    ->default(0),
                TextInput::make('keterangan')
                    ->default(null),
                TextInput::make('id_user')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('id_coa')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('id_program_kerja')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('id_laporan')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('jenis')
                    ->required()
                    ->default(''),
            ]);
    }
}
