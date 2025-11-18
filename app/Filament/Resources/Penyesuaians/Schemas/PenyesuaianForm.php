<?php

namespace App\Filament\Resources\Penyesuaians\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PenyesuaianForm
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
                TextInput::make('debit')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('kredit')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('keterangan')
                    ->default(null),
                TextInput::make('saldo_awal')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('id_coa')
                    ->required()
                    ->numeric(),
                TextInput::make('id_program_kerja')
                    ->required()
                    ->numeric(),
                TextInput::make('id_laporan')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
