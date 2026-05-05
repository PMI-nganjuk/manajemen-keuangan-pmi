<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TransactionTypeEnum: string implements HasLabel
{
    case IN = 'IN';
    case OUT = 'OUT';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::IN => 'Pemasukan',
            self::OUT => 'Pengeluaran',
        };
    }
}
