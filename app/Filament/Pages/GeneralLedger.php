<?php

namespace App\Filament\Pages;

use App\Models\GeneralLedger as GeneralLedgerModel;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum, UnitEnum;

class GeneralLedger extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.general-ledger';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static ?string $navigationLabel = 'Buku Besar (GL)';
    protected static ?string $title = 'Buku Besar (GL)';
    protected static UnitEnum|string|null $navigationGroup = 'Keuangan';

    public function table(Table $table): Table
    {
        return $table
            ->query(GeneralLedgerModel::query()->with(['transaction.programKerja', 'account.categoryTwo.categoryOne', 'transaction.cashAccount', 'transaction.transactionAccount']))
            ->columns([
                TextColumn::make('transaction_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('transaction.document_number')
                    ->label('No Dokumen')
                    ->searchable()
                    ->sortable()
                    ->default('-'),
                
                TextColumn::make('transaction.programKerja.nama_program')
                    ->label('Program Kerja')
                    ->searchable()
                    ->sortable()
                    ->default('-'),
                
                TextColumn::make('transaction.reference')
                    ->label('Referensi')
                    ->searchable()
                    ->default('-'),

                TextColumn::make('account.account_name')
                    ->label('COA Transaksi')
                    ->searchable()
                    ->sortable()
                    ->default('-'),

                TextColumn::make('debit')
                    ->label('Debit')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('credit')
                    ->label('Kredit')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Keterangan')
                    ->searchable()
                    ->default('-'),

                TextColumn::make('saldo_awal')
                    ->label('Saldo Awal')
                    ->state(fn () => 0)
                    ->numeric()
                    ->default(0),

                TextColumn::make('dibayarkan_kepada')
                    ->label('Dibayarkan Kepada')
                    ->default('-'),

                TextColumn::make('transaction.cashAccount.account_name')
                    ->label('Rekening Kas')
                    ->default('-'),

                TextColumn::make('transaction.type')
                    ->label('Kode Transaksi')
                    ->badge()
                    ->default('-'),

                TextColumn::make('transaction.amount')
                    ->label('Rupiah')
                    ->numeric()
                    ->default(0),

                TextColumn::make('transaction.transactionAccount.account_name')
                    ->label('Lawan Transaksi')
                    ->default('-'),

                TextColumn::make('terima_dari')
                    ->label('Terima Dari')
                    ->default('-'),

                TextColumn::make('bs')
                    ->label('BS')
                    ->state(fn ($record) => $record->debit - $record->credit)
                    ->numeric(),

                TextColumn::make('pl')
                    ->label('PL')
                    ->state(fn ($record) => $record->credit - $record->debit)
                    ->numeric(),

                TextColumn::make('account.categoryTwo.categoryOne.category_name')
                    ->label('Kategory')
                    ->default('-'),

                TextColumn::make('inventory')
                    ->label('Inventory')
                    ->default('-'),

                TextColumn::make('hutang')
                    ->label('Hutang')
                    ->default('-'),

                TextColumn::make('piutang')
                    ->label('Piutang')
                    ->default('-'),
            ])
            ->striped()
            ->defaultSort('transaction_date', 'desc');
    }
}
