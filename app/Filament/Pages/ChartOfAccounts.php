<?php

namespace App\Filament\Pages;

use App\Models\CategoryOne;
use App\Models\CategoryTwo;
use App\Models\ChartOfAccounts as ChartOfAccountsModel;
use App\Models\ReportTypes;
use App\Enums\EntryTypeEnum;
use App\Enums\RoleEnum;
use BackedEnum;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use UnitEnum;

class ChartOfAccounts extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected string $view = 'filament.pages.chart-of-accounts';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;
    protected static ?string $navigationLabel = 'Chart of Accounts (COA)';
    protected static ?string $title = 'Chart of Accounts (COA)';
    protected static UnitEnum|string|null $navigationGroup = 'Keuangan';

    public array $formData = [];
    public ?string $editId = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(2)
                ->schema([
                    Select::make('category_one')
                        ->label('Kategori 1')
                        ->options(CategoryOne::pluck('category_name', 'category_code'))
                        ->searchable()
                        ->placeholder('Pilih Kategori 1')
                        ->dehydrated(false)
                        ->createOptionForm([
                            TextInput::make('category_name')
                                ->label('Nama Kategori 1')
                                ->required(),
                        ])
                        ->createOptionUsing(function (array $data) {
                            $category = CategoryOne::create($data);
                            return $category->category_code;
                        })
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, Get $get) {
                            $set('category_two', null);
                            $set('id', null);
                            self::generateSuggestedKode($set, $get);
                        }),

                    Select::make('category_two')
                        ->label('Kategori 2')
                        ->options(fn (Get $get) => CategoryTwo::where('category_one', $get('category_one'))->pluck('category_name', 'category_code'))
                        ->searchable()
                        ->placeholder('Pilih Kategori 2 (pilih Kategori 1 dulu)')
                        ->disabled(fn (Get $get): bool => blank($get('category_one')))
                        ->createOptionForm([
                            TextInput::make('category_name')
                                ->label('Nama Kategori 2')
                                ->required(),
                        ])
                        ->createOptionUsing(function (array $data, Get $get) {
                            $data['category_one'] = $get('category_one');
                            $category = CategoryTwo::create($data);
                            return $category->category_code;
                        })
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, Get $get) =>
                            self::generateSuggestedKode($set, $get)
                        ),

                    TextInput::make('id')
                        ->label('Kode Akun')
                        ->required()
                        ->maxLength(15)
                        ->unique(ChartOfAccountsModel::class, 'id', ignoreRecord: true)
                        ->hint('Kode otomatis disarankan saat Kategori 1 & 2 dipilih.')
                        ->hintIcon(Heroicon::OutlinedInformationCircle)
                        ->placeholder('Contoh: 1101001 - 00'),

                    TextInput::make('account_name')
                        ->label('Nama Akun')
                        ->required()
                        ->maxLength(100),

                    Select::make('entry_type')
                        ->label('Pos Saldo')
                        ->options(EntryTypeEnum::class)
                        ->required()
                        ->native(false),

                    Select::make('report_type_id')
                        ->label('Pos Laporan')
                        ->options(ReportTypes::pluck('report_name', 'id'))
                        ->searchable()
                        ->createOptionForm([
                            TextInput::make('report_name')
                                ->label('Pos Laporan')
                                ->required(),
                        ])
                        ->createOptionUsing(function (array $data) {
                            $report = ReportTypes::create($data);
                            return $report->id;
                        })
                        ->required()
                        ->native(false),
                ])
                ->columns(2),
        ];
    }

    public function form(Schema $schema): Schema
    {
        $isReadOnly = auth()->user()->hasRole(RoleEnum::STAFF);

        return $schema
            ->model(ChartOfAccountsModel::class)
            ->schema($isReadOnly ? [] : $this->getFormSchema())
            ->statePath('formData');
    }

    public static function generateSuggestedKode(Set $set, Get $get): void
    {
        $kat1Id = $get('category_one');
        $kat2Id = $get('category_two');

        if (! $kat1Id || ! $kat2Id) {
            return;
        }

        $prefix = $kat1Id . $kat2Id;

        $lastCoa = ChartOfAccountsModel::query()
            ->where('id', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('id');

        if ($lastCoa) {
            $mainPart = explode(' - ', $lastCoa)[0] ?? '';
            $sequence      = (int) substr($mainPart, strlen($prefix));
            $nextSequence  = str_pad($sequence + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextSequence = '001';
        }

        $set('id', $prefix . $nextSequence . ' - 00');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        DB::beginTransaction();

        try {
            if ($this->editId) {
                $record = ChartOfAccountsModel::findOrFail($this->editId);
                unset($data['category_one']);
                $record->update($data);
                $message = 'COA berhasil diperbarui!';
            } else {
                ChartOfAccountsModel::create($data);
                $message = 'COA berhasil ditambahkan!';
            }
            
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
            Log::error('COA Save Error: ' . $e->getMessage(), [
                'data'  => $data,
                'trace' => $e->getTraceAsString(),
            ]);

            Notification::make()
                ->title('Gagal menyimpan COA!')
                ->body('Pastikan Kode Akun belum dipakai dan semua data valid.')
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
        $isStaff = auth()->user()->hasRole(RoleEnum::STAFF);

        return $table
            ->query(
                ChartOfAccountsModel::query()
                    ->with(['categoryOne', 'categoryTwo', 'reportType'])
            )
            ->columns([
                TextColumn::make('id')
                    ->label('Kode Akun')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Kode disalin!'),

                TextColumn::make('account_name')
                    ->label('Nama Akun')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('categoryTwo.categoryOne.category_name')
                    ->label('Kategori 1')
                    ->toggleable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('categoryTwo.category_name')
                    ->label('Kategori 2')
                    ->toggleable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('entry_type')
                    ->label('Pos Saldo')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state instanceof EntryTypeEnum => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('reportType.report_name')
                    ->label('Pos Laporan')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->striped()
            ->defaultSort('id', 'asc')
            ->recordActions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->color('warning')
                    ->hidden($isStaff)
                    ->action(function (ChartOfAccountsModel $record) {
                        $this->editId = $record->id;
                        $data = $record->attributesToArray();
                        $data['category_one'] = substr($record->id, 0, 1);
                        $this->form->fill($data);
                        
                        $this->dispatch('scroll-to-top');
                    }),

                DeleteAction::make()
                    ->hidden($isStaff)
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->hidden($isStaff),
                ]),
            ]);
    }
}