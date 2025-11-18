<?php

namespace App\Filament\Resources\Coas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CoasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_coa')->label('Kode Akun'),
                TextColumn::make('category1')->label('Kategori 1'),
                TextColumn::make('category2')->label('Kategori 2'),
                TextColumn::make('nama_akun')->label('Nama Akun'),
                TextColumn::make('pos_saldo')->label('Pos Saldo'),
                TextColumn::make('pos_laporan')->label('Pos Laporan'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
