<?php

namespace App\Filament\Resources\PenerimaanKas\Pages;

use App\Filament\Resources\PenerimaanKas\PenerimaanKasResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPenerimaanKas extends EditRecord
{
    protected static string $resource = PenerimaanKasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
