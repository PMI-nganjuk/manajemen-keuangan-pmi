<?php

namespace App\Filament\Resources\Penyesuaians\Pages;

use App\Filament\Resources\Penyesuaians\PenyesuaianResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPenyesuaian extends EditRecord
{
    protected static string $resource = PenyesuaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
