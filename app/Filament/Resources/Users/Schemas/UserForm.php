<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kategori')
                    ->options([
            'karyawan' => 'Karyawan',
            'donatur' => 'Donatur',
            'kreditur' => 'Kreditur',
            'debitur' => 'Debitur',
        ])
                    ->default(null),
                TextInput::make('nama')
                    ->default(null),
                TextInput::make('nomer_wa')
                    ->default(null),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('alamat')
                    ->default(null),
                Select::make('role')
                    ->options([
            'admin' => 'Admin',
            'manager_keuangan' => 'Manager keuangan',
            'staf_keuangan' => 'Staf keuangan',
            'pegawai' => 'Pegawai',
        ])
                    ->default('pegawai')
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(),
            ]);
    }
}
