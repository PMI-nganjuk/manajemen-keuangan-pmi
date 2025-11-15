<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
<<<<<<< HEAD
        Schema::create('penyesuaian', function (Blueprint $table) {
            $table->id('id_penyesuaian');
            $table->date('tanggal');
            $table->string('no_dokumen')->nullable();
            $table->string('referensi')->nullable();
            $table->integer('debit')->default(0);
            $table->integer('kredit')->default(0);
            $table->string('keterangan')->nullable();
            $table->integer('saldo_awal')->default(0);

            // Foreign keys
            $table->unsignedBigInteger('id_coa');
            $table->unsignedBigInteger('id_program_kerja');
            $table->unsignedBigInteger('id_laporan')->nullable();

            // Relations
            $table->foreign('id_coa')->references('id_coa')->on('coa')->onDelete('cascade');
            $table->foreign('id_program_kerja')->references('id_program_kerja')->on('program_kerja')->onDelete('cascade');
            $table->foreign('id_laporan')->references('id_laporan')->on('laporan_keuangan')->onDelete('cascade');

            $table->timestamps();
        });
=======
        if (!Schema::hasTable('penyesuaian')) {
            Schema::create('penyesuaian', function (Blueprint $table) {
                $table->id('id_penyesuaian');
                $table->date('tanggal');
                $table->string('no_dokumen')->nullable();
                $table->string('referensi')->nullable();
                $table->integer('debit')->default(0);
                $table->integer('kredit')->default(0);
                $table->string('keterangan')->nullable();
                $table->integer('saldo_awal')->default(0);
                $table->unsignedBigInteger('id_coa');
                $table->unsignedBigInteger('id_program_kerja');
                $table->foreign('id_coa')->references('id_coa')->on('coa')->onDelete('cascade');
                $table->foreign('id_program_kerja')->references('id_program_kerja')->on('program_kerja')->onDelete('cascade');
                $table->timestamps();
            });
        }
>>>>>>> parent of 82fdd83 (revisi erd and final erd.)
    }

    public function down(): void
    {
        Schema::dropIfExists('penyesuaian');
    }
};
