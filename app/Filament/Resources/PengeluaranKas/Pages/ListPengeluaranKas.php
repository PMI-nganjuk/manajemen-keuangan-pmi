<?php

namespace App\Filament\Resources\PengeluaranKas\Pages;

use App\Filament\Resources\PengeluaranKas\PengeluaranKasResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPengeluaranKas extends ListRecords
{
    protected static string $resource = PengeluaranKasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
