<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gl extends Model
{
    use HasFactory;

    protected $table = 'gl';
    protected $primaryKey = 'id_gl';

    protected $fillable = [
        'no',
        'tanggal',
        'no_dokumen',
        'referensi',
        'kode_transaksi',

        'id_coa',
        'id_program_kerja',
        'id_laporan',

        'id_penerimaan_kas',
        'id_pengeluaran_kas',
        'id_penyesuaian',

        'debit',
        'kredit',
        'rupiah',
        'saldo_awal',

        'keterangan',
        'dibayarkan_kepada',
        'terima_dari',
        'rekening_kas',
        'lawan_transaksi',

        'bs',
        'pl',
        'inventory',

        'hutang',
        'piutang',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'debit' => 'decimal:2',
        'kredit' => 'decimal:2',
        'rupiah' => 'decimal:2',
        'saldo_awal' => 'decimal:2',
        'hutang' => 'decimal:2',
        'piutang' => 'decimal:2',
    ];

    // Relasi 1 -> N (Inverse)
    public function coa()
    {
        return $this->belongsTo(ChartOfAccounts::class, 'id_coa', 'id');
    }

    // Relasi 1 -> N (Inverse)
    public function programKerja()
    {
        return $this->belongsTo(ProgramKerja::class, 'id_program_kerja', 'id_program_kerja');
    }

    // Relasi 1 -> N (Inverse)
    public function laporanKeuangan()
    {
        return $this->belongsTo(LaporanKeuangan::class, 'id_laporan', 'id_laporan');
    }

   // Relasi yang digunakan untuk 1 transaksi hanya berasal dari salah satu sumber transaksi

    // Relasi Sumber Transaksi (Inverse)
    public function penerimaanKas()
    {
        return $this->belongsTo(PenerimaanKas::class, 'id_penerimaan_kas', 'id_pemasukan');
    }



    // Relasi 1 -> N (Inverse)
    public function pengeluaranKas()
    {
        return $this->belongsTo(PengeluaranKas::class, 'id_pengeluaran_kas', 'id_pengeluaran');
    }

    // Relasi 1 -> N (Inverse)
    public function penyesuaian()
    {
        return $this->belongsTo(Penyesuaian::class, 'id_penyesuaian', 'id_penyesuaian');
    }
}
