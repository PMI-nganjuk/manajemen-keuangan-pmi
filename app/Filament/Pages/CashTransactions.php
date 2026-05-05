<?php

namespace App\Filament\Pages;

use App\Enums\RoleEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\ChartOfAccounts as ChartOfAccountsModel;
use App\Models\ProgramKerja;
use App\Models\Transaction;
use App\Models\GeneralLedger;
use App\Models\User;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use UnitEnum;
use BackedEnum;

class CashTransactions extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected string $view = 'filament.pages.cash-transactions';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;
    protected static ?string $navigationLabel = 'Transaksi Kas';
    protected static ?string $title = 'Penerimaan & Pengeluaran Kas';
    protected static UnitEnum|string|null $navigationGroup = 'Keuangan';

    public array $formData = [];
    public ?int $editId = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(2)
                ->schema([
                    Select::make('type')
                        ->label('Jenis Transaksi')
                        ->options(TransactionTypeEnum::class)
                        ->required()
                        ->live(),

                    DatePicker::make('transaction_date')
                        ->label('Tanggal Transaksi')
                        ->required()
                        ->default(now()),

                    Select::make('id_user')
                        ->label(fn (Get $get) => $get('type') === 'OUT' ? 'Dibayarkan Kepada' : 'Terima Dari')
                        ->options(User::query()->pluck('nama', 'id_user')->map(fn($nama) => $nama ?? 'Tanpa Nama')->toArray())
                        ->searchable()
                        ->required(),

                    Select::make('id_program_kerja')
                        ->label('Program Kerja')
                        ->options(ProgramKerja::pluck('nama_program', 'id_program_kerja'))
                        ->searchable()
                        ->placeholder('Pilih Program Kerja (Opsional)'),

                    Select::make('cash_account_id')
                        ->label('Rekening Kas')
                        ->options(ChartOfAccountsModel::selectRaw('id, CONCAT(id, " - ", account_name) as full_name')->pluck('full_name', 'id'))
                        ->searchable()
                        ->required(),

                    Select::make('transaction_account_id')
                        ->label('Kode Transaksi')
                        ->options(ChartOfAccountsModel::selectRaw('id, CONCAT(id, " - ", account_name) as full_name')->pluck('full_name', 'id'))
                        ->searchable()
                        ->required(),

                    TextInput::make('reference')
                        ->label('Referensi')
                        ->maxLength(255),

                    TextInput::make('amount')
                        ->label('Rupiah (Nominal)')
                        ->required()
                        ->numeric()
                        ->minValue(0),

                    Textarea::make('description')
                        ->label('Keterangan')
                        ->columnSpanFull()
                        ->maxLength(65535),
                ]),
        ];
    }

    public function form(Schema $schema): Schema
    {
        // Adjust role check according to actual RoleEnum implementation if needed
        $isReadOnly = auth()->user()?->hasRole(RoleEnum::STAFF) ?? false;

        return $schema
            ->model(Transaction::class)
            ->schema($isReadOnly ? [] : $this->getFormSchema())
            ->statePath('formData');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        DB::beginTransaction();

        try {
            $typeEnum = TransactionTypeEnum::from($data['type']->value);

            if ($this->editId) {
                $transaction = Transaction::findOrFail($this->editId);
                // Cannot easily change type/document number after creation, but if needed we can regenerate
                // For safety, we keep the original document number
                $transaction->update($data);
                
                // Re-create GL entries
                $transaction->generalLedgers()->delete();

                $message = 'Transaksi berhasil diperbarui!';
            } else {
                $data['document_number'] = Transaction::generateDocumentNumber($typeEnum);
                $transaction = Transaction::create($data);
                $message = 'Transaksi berhasil ditambahkan!';
            }

            // Create General Ledger Entries
            // Pemasukan (IN): Debit = Rekening Kas, Kredit = Kode Transaksi
            // Pengeluaran (OUT): Debit = Kode Transaksi, Kredit = Rekening Kas
            
            $debitAccount = $typeEnum === TransactionTypeEnum::IN ? $data['cash_account_id'] : $data['transaction_account_id'];
            $creditAccount = $typeEnum === TransactionTypeEnum::IN ? $data['transaction_account_id'] : $data['cash_account_id'];

            // Debit Entry
            GeneralLedger::create([
                'transaction_id' => $transaction->id,
                'account_id' => $debitAccount,
                'transaction_date' => $data['transaction_date'],
                'debit' => $data['amount'],
                'credit' => 0,
                'description' => $data['description'],
            ]);

            // Credit Entry
            GeneralLedger::create([
                'transaction_id' => $transaction->id,
                'account_id' => $creditAccount,
                'transaction_date' => $data['transaction_date'],
                'debit' => 0,
                'credit' => $data['amount'],
                'description' => $data['description'],
            ]);

            DB::commit();

            $this->editId = null;
            $this->reset('formData');
            $this->form->fill();

            Notification::make()
                ->title($message)
                ->success()
                ->send();

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Transaction Save Error: ' . $e->getMessage(), [
                'data'  => $data,
                'trace' => $e->getTraceAsString(),
            ]);

            Notification::make()
                ->title('Gagal menyimpan Transaksi!')
                ->body('Pastikan data valid dan tidak ada duplikasi.')
                ->danger()
                ->send();
        }
    }

    public function cancelEdit(): void
    {
        $this->editId = null;
        $this->reset('formData');
        $this->form->fill();
    }

    public function table(Table $table): Table
    {
        $isStaff = auth()->user()?->hasRole(RoleEnum::STAFF) ?? false;

        return $table
            ->query(
                Transaction::query()->with(['user', 'programKerja', 'cashAccount', 'transactionAccount'])
            )
            ->columns([
                TextColumn::make('transaction_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('document_number')
                    ->label('No Dokumen')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (TransactionTypeEnum $state): string => match ($state) {
                        TransactionTypeEnum::IN => 'success',
                        TransactionTypeEnum::OUT => 'danger',
                    }),

                TextColumn::make('programKerja.nama_program')
                    ->label('Program Kerja')
                    ->toggleable(),

                TextColumn::make('user.nama')
                    ->label('Terima Dari / Kepada')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('cashAccount.account_name')
                    ->label('Rekening Kas')
                    ->toggleable(),

                TextColumn::make('transactionAccount.account_name')
                    ->label('Kode Transaksi')
                    ->toggleable(),

                TextColumn::make('amount')
                    ->label('Rupiah')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Keterangan')
                    ->limit(30)
                    ->toggleable(),
            ])
            ->striped()
            ->defaultSort('transaction_date', 'desc')
            ->recordActions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->color('warning')
                    ->hidden($isStaff)
                    ->action(function (Transaction $record) {
                        $this->editId = $record->id;
                        $data = $record->attributesToArray();
                        $this->form->fill($data);
                        
                        $this->dispatch('scroll-to-top');
                    }),

                DeleteAction::make()
                    ->hidden($isStaff)
                    ->requiresConfirmation()
                    ->action(function (Transaction $record) {
                        // GeneralLedgers are deleted via cascade on DB level or Eloquent relationship
                        $record->delete();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->hidden($isStaff),
                ]),
            ]);
    }
}
