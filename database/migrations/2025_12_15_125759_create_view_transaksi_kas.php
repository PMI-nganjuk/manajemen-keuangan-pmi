<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan view lama dihapus dulu (WAJIB)
        DB::statement('DROP VIEW IF EXISTS view_transaksi_kas');

        DB::statement("
            CREATE VIEW view_transaksi_kas AS
            SELECT
                id_pemasukan AS id,
                tanggal,
                no_dokumen,
                referensi,
                rupiah,
                keterangan,
                id_user,
                id_coa,
                id_program_kerja,
                id_laporan,
                'MASUK' AS jenis_transaksi
            FROM penerimaan_kas

            UNION ALL

            SELECT
                id_pengeluaran AS id,
                tanggal,
                no_dokumen,
                referensi,
                rupiah * -1 AS rupiah,
                keterangan,
                id_user,
                id_coa,
                id_program_kerja,
                id_laporan,
                'KELUAR' AS jenis_transaksi
            FROM pengeluaran_kas
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_transaksi_kas');
    }
};
