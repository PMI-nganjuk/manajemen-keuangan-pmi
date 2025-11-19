<?php

namespace App\Models;

use App\Enums\RoleEnum;
use App\Enums\KategoriEnum;
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

    // Accessor untuk name di Laravel default.
    public function getNameAttribute()
    {
        return $this->nama ?? 'User';
    }

    // Mengambil seluruh nilai role (untuk validasi / select Filament).
    public static function getRoles(): array
    {
        return RoleEnum::values();
    }

    // Mengambil seluruh kategori (untuk validasi / select Filament).
    public static function getKategori(): array
    {
        return KategoriEnum::values();
    }

    // Cek apakah user memiliki role tertentu.
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    // Hash password otomatis ketika diset.
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::needsRehash($value)
                ? Hash::make($value)
                : $value;
        }
    }

    // Relasi 1 - N ke Program Kerja
    public function programKerja()
    {
        return $this->hasMany(ProgramKerja::class, 'id_pegawai', 'id_user');
    }

    // Relasi 1 - N ke Penerimaan Kas
    public function penerimaanKas()
    {
        return $this->hasMany(PenerimaanKas::class, 'id_user', 'id_user');
    }

    // Relasi 1 - N ke Pengeluaran Kas
    public function pengeluaranKas()
    {
        return $this->hasMany(PengeluaranKas::class, 'id_user', 'id_user');
    }
}
