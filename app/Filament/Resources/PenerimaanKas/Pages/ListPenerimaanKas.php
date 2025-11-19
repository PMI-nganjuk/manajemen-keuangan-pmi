<?php

namespace App\Filament\Resources\PenerimaanKas\Pages;

use App\Filament\Resources\PenerimaanKas\PenerimaanKasResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPenerimaanKas extends ListRecords
{
    protected static string $resource = PenerimaanKasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
