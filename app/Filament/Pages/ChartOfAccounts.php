<?php

namespace App\Filament\Pages;

use App\Models\CategoryOne;
use App\Models\CategoryTwo;
use App\Models\ChartOfAccounts as ChartOfAccountsModel;
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
                        ->relationship('categoryOne', 'category_name')
                        ->searchable()
                        ->preload()
                        ->optionsLimit(10)
                        ->placeholder('Pilih Kategori 1')
                        ->dehydrated(false)
                        ->createOptionForm([
                            TextInput::make('category_name')
                                ->label('Nama Kategori 1')
                                ->required(),
                        ])
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, Get $get) {
                            $set('category_two', null);
                            $set('id', null);
                            self::generateSuggestedKode($set, $get);
                        }),

                    Select::make('category_two')
                        ->label('Kategori 2')
                        ->relationship(
                            name: 'categoryTwo',
                            titleAttribute: 'category_name',
                            modifyQueryUsing: fn (Builder $query, Get $get) =>
                                $query->where('category_one', $get('category_one'))
                        )
                        ->searchable()
                        ->preload()
                        ->optionsLimit(10)
                        ->placeholder('Pilih Kategori 2 (pilih Kategori 1 dulu)')
                        ->disabled(fn (Get $get): bool => blank($get('category_one')))
                        ->createOptionForm([
                            TextInput::make('category_name')
                                ->label('Nama Kategori 2')
                                ->required(),
                        ])
                        ->createOptionAction(
                            fn (Action $action) => $action
                                ->mutateFormDataUsing(function (array $data, Get $get): array {
                                    $data['category_one'] = $get('category_one');
                                    return $data;
                                })
                        )
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
                        ->relationship('reportType', 'report_name')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('report_name')
                                ->label('Pos Laporan')
                                ->required(),
                        ])
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

    public function create(): void
    {
        $data = $this->form->getState();

        DB::beginTransaction();

        try {
            ChartOfAccountsModel::create($data);
            DB::commit();

            $this->reset('formData');
            $this->form->fill();

            Notification::make()
                ->title('COA berhasil ditambahkan!')
                ->success()
                ->send();

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('COA Create Error: ' . $e->getMessage(), [
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
                EditAction::make()
                    ->hidden($isStaff)
                    ->color('warning')
                    ->form($this->getFormSchema())
                    ->mutateRecordDataUsing(function (array $data, $record): array {
                        $data['category_one'] = substr($record->id, 0, 1);
                        return $data;
                    })
                    ->using(function (Model $record, array $data): Model {
                        unset($data['category_one']);
                        $record->offsetUnset('category_one');
                        $record->update($data);
                        return $record;
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