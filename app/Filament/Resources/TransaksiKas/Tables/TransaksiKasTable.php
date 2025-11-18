<?php

namespace App\Filament\Resources\TransaksiKas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransaksiKasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal')->date(),
                TextColumn::make('no_dokumen'),
                TextColumn::make('referensi'),
                TextColumn::make('rupiah')->money('IDR'),
                TextColumn::make('keterangan'),
                TextColumn::make('user.nama')->label('User'),
                TextColumn::make('coa.nama_coa')->label('CoA'),
                TextColumn::make('jenis')->badge(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

