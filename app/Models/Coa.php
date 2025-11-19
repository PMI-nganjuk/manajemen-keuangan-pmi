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
        'nama_akun',
        'pos_saldo',
        'pos_laporan',
    ];

    // Coa 1 - n PenerimaanKas
    public function penerimaanKas()
    {
        return $this->hasMany(PenerimaanKas::class, 'id_coa', 'id_coa');
    }

    // Coa 1 - n PengeluaranKas
    public function pengeluaranKas()
    {
        return $this->hasMany(PengeluaranKas::class, 'id_coa', 'id_coa');
    }

    // Coa 1 - n Penyesuaian
    public function penyesuaian()
    {
        return $this->hasMany(Penyesuaian::class, 'id_coa', 'id_coa');
    }

    // Coa 1 - n KategoriDua
    public function kategoriDua()
    {
        return $this->hasMany(KategoriDua::class, 'id_coa', 'id_coa');
    }
}
