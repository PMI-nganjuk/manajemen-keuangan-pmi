<?php

namespace App\Filament\Resources\Gls\Pages;

use App\Filament\Resources\Gls\GlResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageGls extends ManageRecords
{
    protected static string $resource = GlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
