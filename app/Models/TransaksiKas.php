<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiKas extends Model
{
    protected $table = 'view_transaksi_kas';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    // Fillable fields
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // n - 1 CoA
    public function coa()
    {
        return $this->belongsTo(Coa::class, 'id_coa', 'id_coa');
    }

    // n - 1 Program Kerja
    public function programKerja()
    {
        return $this->belongsTo(ProgramKerja::class, 'id_program_kerja', 'id_program_kerja');
    }

    // n - 1 Laporan Keuangan
    public function laporanKeuangan()
    {
        return $this->belongsTo(LaporanKeuangan::class, 'id_laporan', 'id_laporan');
    }
}
