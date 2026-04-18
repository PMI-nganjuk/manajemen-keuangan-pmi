<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenerimaanKas extends Model
{
    use HasFactory;

    protected $table = 'penerimaan_kas';
    protected $primaryKey = 'id_pemasukan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'tanggal',
        'no_dokumen',
        'referensi',
        'rupiah',
        'keterangan',
        'id_user',
        'id_coa',
        'id_program_kerja',
        'id_laporan',
    ];


    // n - 1 user
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

    // 1 - n GL
    public function gl()
    {
        return $this->hasMany(Gl::class, 'id_penerimaan_kas', 'id_penerimaan_kas');
    }

}
