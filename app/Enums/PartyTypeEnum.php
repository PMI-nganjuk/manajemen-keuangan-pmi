<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PartyTypeEnum: string implements HasLabel
{
    case EMPLOYEE = 'Karyawan';
    case DONOR = 'Donatur';
    case CREDITOR = 'Kreditur';
    case DEBTOR = 'Debitur';

    public function getLabel(): ?string
    {
        return $this->value;
    }
}