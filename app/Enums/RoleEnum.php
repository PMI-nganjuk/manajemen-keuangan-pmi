<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RoleEnum: string implements HasLabel
{
    case ADMIN = 'Admin';
    case FINANCIAL_MANAGER = 'Manager keuangan';
    case FINANCE_STAFF = 'Staf Keuangan';
    case STAFF = 'Pegawai';

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
