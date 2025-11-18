<?php

namespace App\Filament\Resources\PengeluaranKas\Pages;

use App\Filament\Resources\PengeluaranKas\PengeluaranKasResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPengeluaranKas extends EditRecord
{
    protected static string $resource = PengeluaranKasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
