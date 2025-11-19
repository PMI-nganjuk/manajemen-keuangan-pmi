<?php

namespace App\Enums;

class RoleEnum
{
    public const ADMIN = 'admin';
    public const MANAGER_KEUANGAN = 'manager_keuangan';
    public const STAF_KEUANGAN = 'staf_keuangan';
    public const PEGAWAI = 'pegawai';

    public static function values(): array
    {
        return [
            self::ADMIN,
            self::MANAGER_KEUANGAN,
            self::STAF_KEUANGAN,
            self::PEGAWAI,
        ];
    }

    public static function labels(): array
    {
        return [
            self::ADMIN => 'Admin',
            self::MANAGER_KEUANGAN => 'Manager Keuangan',
            self::STAF_KEUANGAN => 'Staf Keuangan',
            self::PEGAWAI => 'Pegawai',
        ];
    }
}
