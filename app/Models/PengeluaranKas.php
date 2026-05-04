<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengeluaranKas extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_kas';
    protected $primaryKey = 'id_pengeluaran';
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


    // n - 1 User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // n - 1 CoA
    public function coa()
    {
        return $this->belongsTo(ChartOfAccounts::class, 'id_coa', 'id');
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
        return $this->hasMany(Gl::class, 'id_pengeluaran_kas', 'id_pengeluaran');
    }

}
