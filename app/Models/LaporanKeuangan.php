<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKeuangan extends Model
{
    protected $table = 'laporan_keuangan';
    protected $primaryKey = 'id_laporan';

    public function penyesuaian()
    {
        return $this->belongsTo(Penyesuaian::class, 'id_penyesuaian');
    }
}
