<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKeuangan extends Model
{
    protected $table = 'laporan_keuangan';
    protected $primaryKey = 'id_laporan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'kas_tahun1',
        'kas_tahun2',
        'saldo_akhir',
    ];
    
    // 1 - n Penyesuaian
    public function penyesuaian()
    {
        return $this->hasMany(Penyesuaian::class, 'id_laporan', 'id_laporan');
    }

    // 1 - n PengeluaranKas
    public function pengeluaranKas()
    {
        return $this->hasMany(PengeluaranKas::class, 'id_laporan', 'id_laporan');
    }

    // 1 - n PenerimaanKas
    public function penerimaanKas()
    {
        return $this->hasMany(PenerimaanKas::class, 'id_laporan', 'id_laporan');
    }
}
