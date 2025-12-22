<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penyesuaian extends Model
{
    use HasFactory;

    protected $table = 'penyesuaian';
    protected $primaryKey = 'id_penyesuaian';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'tanggal',
        'no_dokumen',
        'referensi',
        'debit',
        'kredit',
        'keterangan',
        'saldo_awal',
        'id_coa',
        'id_program_kerja',
        'id_laporan',
    ];

    // n - 1 Laporan Keuangan
    public function laporanKeuangan()
    {
        return $this->belongsTo(LaporanKeuangan::class, 'id_laporan', 'id_laporan');
    }

    // alias biar bisa dipanggil 'laporan' di controller
    public function laporan()
    {
        return $this->belongsTo(LaporanKeuangan::class, 'id_laporan', 'id_laporan');
    }

    public function programKerja()
    {
        return $this->belongsTo(ProgramKerja::class, 'id_program_kerja', 'id_program_kerja');
    }

    public function coa()
    {
        return $this->belongsTo(Coa::class, 'id_coa', 'id_coa');
    }
}
