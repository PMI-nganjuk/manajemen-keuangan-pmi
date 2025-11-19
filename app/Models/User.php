<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'kategori',
        'nomer_wa',
        'alamat',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getNameAttribute()
    {
        return $this->nama ?? 'User';
    }

    // Role
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MANAGER_KEUANGAN = 'manager_keuangan';
    public const ROLE_STAF_KEUANGAN = 'staf_keuangan';
    public const ROLE_PEGAWAI = 'pegawai';

    // Kategori
    public const KATEGORI_KARYAWAN = 'karyawan';
    public const KATEGORI_DONATUR = 'donatur';
    public const KATEGORI_KREDITUR = 'kreditur';
    public const KATEGORI_DEBITUR = 'debitur';

    // Untuk mengambil semua list role
    public static function getRoles(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_MANAGER_KEUANGAN,
            self::ROLE_STAF_KEUANGAN,
            self::ROLE_PEGAWAI,
        ];
    }

    // Untuk mengambil semua list kategori
    public static function getKategori(): array
    {
        return [
            self::KATEGORI_KARYAWAN,
            self::KATEGORI_DONATUR,
            self::KATEGORI_KREDITUR,
            self::KATEGORI_DEBITUR,
        ];
    }

    public function hasRole($role): bool
    {
        return $this->role === $role;
    }

   // encrypt password saat diset
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::needsRehash($value)
                ? Hash::make($value)
                : $value;
        }
    }


    // 1 - n program kerja
    public function programKerja()
    {
        return $this->hasMany(ProgramKerja::class, 'id_pegawai', 'id_user');
    }

    // 1 - n penerimaan kas
    public function penerimaanKas()
    {
        return $this->hasMany(PenerimaanKas::class, 'id_user', 'id_user');
    }

    // 1 - n pengeluaran kas
    public function pengeluaranKas()
    {
        return $this->hasMany(PengeluaranKas::class, 'id_user', 'id_user');
    }
}
