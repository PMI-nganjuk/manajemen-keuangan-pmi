<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum EntryTypeEnum: string implements HasLabel
{
    case DEBIT = 'D';
    case CREDIT = 'K';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DEBIT => 'Debit',
            self::CREDIT => 'Kredit',
        };
    }
}