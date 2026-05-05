<?php

namespace App\Filament\Pages;

use App\Enums\RoleEnum;
use App\Models\OrganizationProfile as OrganizationProfileModel;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput; 
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class OrganizationProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.organization-profile';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;
    protected static ?string $navigationLabel = 'Profil Organisasi';
    protected static ?string $title = 'Profil Organisasi';
    protected static string | UnitEnum | null $navigationGroup = 'Program & Organisasi';

    public ?array $data = [];
    public ?OrganizationProfileModel $profile;

    public function mount(): void
    {
        $this->profile = OrganizationProfileModel::first() ?? OrganizationProfileModel::create([]);

        $this->form->fill($this->profile->toArray());
    } 

    protected function getFormSchema(): array
    {
        return [
            Grid::make(1)
                 ->schema([
                    Section::make('Informasi Umum')
                        ->schema([
                            TextInput::make('organization_name')
                                ->label('Nama Entitas'),
                            Textarea::make('address')
                                ->label('Alamat')
                                ->rows(3),
                    ]),
                    Section::make('Pengurus')
                        ->schema([
                            TextInput::make('chairperson')->label('Ketua'),
                            TextInput::make('headquarters_treasurer')->label('Bendahara Markas'),
                            TextInput::make('blood_donation_unit_treasurer')->label('Bendahara UUD'),
                        ])
                        ->columns(2),
                    Section::make('Periode Buku')
                        ->schema([
                            DatePicker::make('financial_period_start')
                                ->label('Periode Awal')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (?string $state, Set $set) {
                                    if ($state) {
                                        $year = \Carbon\Carbon::parse($state)->format('Y');
                                        $set('fiscal_year', $year);
                                    } else {
                                        $set('fiscal_year', null);
                                    }
                            }),
                            DatePicker::make('financial_period_end')->label('Periode Akhir'),
                            TextInput::make('fiscal_year')->label('Tahun Buku')->type('number'),
                        ])
                        ->columns(3),
                ])
        ];
    }

    public function form(Schema $schema): Schema
    {
        $isReadOnly = !auth()->user()->hasRole(RoleEnum::ADMIN);

        return $schema
            ->disabled($isReadOnly)
            ->model(OrganizationProfileModel::class)
            ->schema($this->getFormSchema())
            ->statePath('data');
    }

    public function save(): void
    {
        $formData = $this->form->getState();
        $profile = OrganizationProfileModel::first();

        if ($profile) {
            $profile->update($formData);

            Notification::make()
                ->title('Profil berhasil diperbarui!')
                ->success()
                ->send();
        }
    }
}