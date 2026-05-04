<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gl', function (Blueprint $table) {
            $table->id('id_gl');

            $table->integer('no')->nullable();
            $table->date('tanggal');

            $table->string('no_dokumen', 50)->nullable();
            $table->string('referensi', 100)->nullable();
            $table->string('kode_transaksi', 50)->nullable();

            // RELASI UTAMA
            $table->string('id_coa');
            $table->unsignedBigInteger('id_program_kerja')->nullable();
            $table->unsignedBigInteger('id_laporan')->nullable();

            // RELASI OPSIONAL (SALAH SATU TERISI)
            $table->unsignedBigInteger('id_penerimaan_kas')->nullable();
            $table->unsignedBigInteger('id_pengeluaran_kas')->nullable();
            $table->unsignedBigInteger('id_penyesuaian')->nullable();

            // NILAI AKUNTANSI
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('kredit', 15, 2)->default(0);
            $table->decimal('rupiah', 15, 2)->default(0);
            $table->decimal('saldo_awal', 15, 2)->default(0);

            // INFO TAMBAHAN
            $table->text('keterangan')->nullable();
            $table->string('dibayarkan_kepada', 100)->nullable();
            $table->string('terima_dari', 100)->nullable();
            $table->string('rekening_kas', 50)->nullable();
            $table->string('lawan_transaksi', 100)->nullable();

            $table->string('bs', 50)->nullable();
            $table->string('pl', 50)->nullable();
            $table->string('inventory', 100)->nullable();

            $table->decimal('hutang', 15, 2)->default(0);
            $table->decimal('piutang', 15, 2)->default(0);

            $table->timestamps();

            // FOREIGN KEY
            $table->foreign('id_coa')
                  ->references('id')
                  ->on('chart_of_accounts')
                  ->cascadeOnDelete();
            $table->foreign('id_program_kerja')->references('id_program_kerja')->on('program_kerja')->nullOnDelete();
            $table->foreign('id_laporan')->references('id_laporan')->on('laporan_keuangan')->nullOnDelete();

            $table->foreign('id_penerimaan_kas')->references('id_pemasukan')->on('penerimaan_kas')->nullOnDelete();
            $table->foreign('id_pengeluaran_kas')->references('id_pengeluaran')->on('pengeluaran_kas')->nullOnDelete();
            $table->foreign('id_penyesuaian')->references('id_penyesuaian')->on('penyesuaian')->nullOnDelete();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('gl');
    }
};
