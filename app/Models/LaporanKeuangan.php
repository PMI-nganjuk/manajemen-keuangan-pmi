<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class LaporanKeuangan extends Model
{
    use HasFactory;

    protected $table = 'laporan_keuangan';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'periode',
        'tahun',
        'status',
        'kas_tahun1',
        'kas_tahun2',
        'saldo_akhir',
    ];

    // 1 - n penyesuaian
    public function penyesuaian()
    {
        return $this->hasMany(Penyesuaian::class, 'id_laporan');
    }

    //  1 - n pengeluaranKas
    public function pengeluaranKas()
    {
        return $this->hasMany(PengeluaranKas::class, 'id_laporan');
    }

    //  1 - n penerimaanKas
    public function penerimaanKas()
    {
        return $this->hasMany(PenerimaanKas::class, 'id_laporan');
    }

    // 1 - n GL
    public function gl()
    {
        return $this->hasMany(Gl::class, 'id_laporan', 'id_laporan');
    }
}
