<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiKas extends Model
{
    protected $table = 'view_transaksi_kas';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function coa()
    {
        return $this->belongsTo(Coa::class, 'id_coa', 'id_coa');
    }

    public function programKerja()
    {
        return $this->belongsTo(ProgramKerja::class, 'id_program_kerja', 'id_program_kerja');
    }

    public function laporanKeuangan()
    {
        return $this->belongsTo(LaporanKeuangan::class, 'id_laporan_keuangan', 'id_laporan_keuangan');
    }
}