<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('penerimaan_kas')) {
            Schema::create('penerimaan_kas', function (Blueprint $table) {
                $table->id('id_pemasukan');
                $table->date('tanggal');
                $table->string('no_dokumen')->nullable(); // corrected from decimal
                $table->string('referensi')->nullable();
                $table->integer('rupiah');
                $table->string('keterangan')->nullable();
                $table->unsignedBigInteger('id_user');
                $table->unsignedBigInteger('id_coa');
                $table->unsignedBigInteger('id_program_kerja');
                $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
                $table->foreign('id_coa')->references('id_coa')->on('coa')->onDelete('cascade');
                $table->foreign('id_program_kerja')->references('id_program_kerja')->on('program_kerja')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('penerimaan_kas');
    }
};
