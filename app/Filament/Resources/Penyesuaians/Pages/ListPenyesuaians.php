<?php

namespace App\Filament\Resources\Penyesuaians\Pages;

use App\Filament\Resources\Penyesuaians\PenyesuaianResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPenyesuaians extends ListRecords
{
    protected static string $resource = PenyesuaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
