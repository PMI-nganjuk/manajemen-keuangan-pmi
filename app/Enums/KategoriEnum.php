<?php

namespace App\Enums;

class KategoriEnum
{
    public const KARYAWAN = 'karyawan';
    public const DONATUR = 'donatur';
    public const KREDITUR = 'kreditur';
    public const DEBITUR = 'debitur';

    public static function values(): array
    {
        return [
            self::KARYAWAN,
            self::DONATUR,
            self::KREDITUR,
            self::DEBITUR,
        ];
    }

    public static function labels(): array
    {
        return [
            self::KARYAWAN => 'Karyawan',
            self::DONATUR => 'Donatur',
            self::KREDITUR => 'Kreditur',
            self::DEBITUR => 'Debitur',
        ];
    }
}
