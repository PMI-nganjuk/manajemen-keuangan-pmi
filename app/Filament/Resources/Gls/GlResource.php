<?php

namespace App\Filament\Resources\Gls;

use App\Filament\Resources\Gls\Pages\ManageGls;
use App\Models\Gl;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GlResource extends Resource
{
    protected static ?string $model = Gl::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $navigationLabel = 'Buku Besar';
    protected static ?string $pluralModelLabel = 'Buku Besar';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('no')
                    ->numeric()
                    ->default(null),
                DatePicker::make('tanggal')
                    ->required(),
                TextInput::make('no_dokumen')
                    ->default(null),
                TextInput::make('referensi')
                    ->default(null),
                TextInput::make('kode_transaksi')
                    ->default(null),
                TextInput::make('id_coa')
                    ->required(),
                TextInput::make('id_program_kerja')
                    ->numeric()
                    ->default(null),
                TextInput::make('id_laporan')
                    ->numeric()
                    ->default(null),
                TextInput::make('id_penerimaan_kas')
                    ->numeric()
                    ->default(null),
                TextInput::make('id_pengeluaran_kas')
                    ->numeric()
                    ->default(null),
                TextInput::make('id_penyesuaian')
                    ->numeric()
                    ->default(null),
                TextInput::make('debit')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('kredit')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('rupiah')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('saldo_awal')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Textarea::make('keterangan')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('dibayarkan_kepada')
                    ->default(null),
                TextInput::make('terima_dari')
                    ->default(null),
                TextInput::make('rekening_kas')
                    ->default(null),
                TextInput::make('lawan_transaksi')
                    ->default(null),
                TextInput::make('bs')
                    ->default(null),
                TextInput::make('pl')
                    ->default(null),
                TextInput::make('inventory')
                    ->default(null),
                TextInput::make('hutang')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('piutang')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('no_dokumen')
                    ->searchable(),
                TextColumn::make('referensi')
                    ->searchable(),
                TextColumn::make('kode_transaksi')
                    ->searchable(),
                TextColumn::make('id_coa')
                    ->searchable(),
                TextColumn::make('id_program_kerja')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('id_laporan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('id_penerimaan_kas')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('id_pengeluaran_kas')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('id_penyesuaian')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('debit')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kredit')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rupiah')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('saldo_awal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dibayarkan_kepada')
                    ->searchable(),
                TextColumn::make('terima_dari')
                    ->searchable(),
                TextColumn::make('rekening_kas')
                    ->searchable(),
                TextColumn::make('lawan_transaksi')
                    ->searchable(),
                TextColumn::make('bs')
                    ->searchable(),
                TextColumn::make('pl')
                    ->searchable(),
                TextColumn::make('inventory')
                    ->searchable(),
                TextColumn::make('hutang')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('piutang')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageGls::route('/'),
        ];
    }
}
