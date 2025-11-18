<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Run the migrations.
    public function up(): void
    {
        if (!Schema::hasTable('penerimaan_kas')) {
            Schema::create('penerimaan_kas', function (Blueprint $table) {
                $table->id('id_pemasukan');
                $table->date('tanggal');
                $table->string('no_dokumen')->nullable();
                $table->string('referensi')->nullable();
                $table->integer('rupiah');
                $table->string('keterangan')->nullable();

                // Foreign keys
                $table->unsignedBigInteger('id_user');
                $table->unsignedBigInteger('id_coa');
                $table->unsignedBigInteger('id_program_kerja');
                $table->unsignedBigInteger('id_laporan');

                // Relasi FK
                $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
                $table->foreign('id_coa')->references('id_coa')->on('coa')->onDelete('cascade');
                $table->foreign('id_program_kerja')->references('id_program_kerja')->on('program_kerja')->onDelete('cascade');
                $table->foreign('id_laporan')->references('id_laporan')->on('laporan_keuangan')->onDelete('cascade');

                $table->timestamps();
            });
        }
    }

    // Reverse the migrations.
    public function down(): void
    {
        Schema::dropIfExists('penerimaan_kas');
    }
};
