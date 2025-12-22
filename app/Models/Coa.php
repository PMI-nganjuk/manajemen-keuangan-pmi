<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coa extends Model
{
    use HasFactory;

    protected $table = 'coa';
    protected $primaryKey = 'id_coa';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_coa',
        'kategori_1',
        'kategori_2',
        'nama_akun',
        'pos_saldo',
        'pos_laporan',
    ];

    // Relasi 1 -> N
    public function penerimaanKas()
    {
        return $this->hasMany(PenerimaanKas::class, 'id_coa', 'id_coa');
    }

    // Relasi 1 -> N
    public function pengeluaranKas()
    {
        return $this->hasMany(PengeluaranKas::class, 'id_coa', 'id_coa');
    }

    // Relasi 1 -> N
    public function penyesuaian()
    {
        return $this->hasMany(Penyesuaian::class, 'id_coa', 'id_coa');
    }

    // Relasi 1 -> N
    public function kategoriDua()
    {
        return $this->hasMany(KategoriDua::class, 'id_coa', 'id_coa');
    }

    // Relasi 1 -> N (Inverse)
    public function gl()
    {
        return $this->hasMany(Gl::class, 'id_coa', 'id_coa');
    }

}
