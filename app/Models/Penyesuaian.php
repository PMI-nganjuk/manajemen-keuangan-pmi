<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyesuaian extends Model
{
    protected $table = 'penyesuaian';
    protected $primaryKey = 'id_penyesuaian';

    public function laporanKeuangan()
    {
        return $this->hasMany(LaporanKeuangan::class, 'id_penyesuaian');
    }
}
