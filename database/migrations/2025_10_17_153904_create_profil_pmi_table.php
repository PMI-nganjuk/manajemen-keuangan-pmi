<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('profil_pmi')) {
            Schema::create('profil_pmi', function (Blueprint $table) {
                $table->id('id_profil');
                $table->string('nama_pmi');
                $table->string('alamat');
                $table->string('ketua');
                $table->text('kepala_markas')->nullable();
                $table->string('kepala_uud')->nullable();
                $table->string('bendahara_markas')->nullable();
                $table->string('bendahara_uud')->nullable();
                $table->date('periode_buku_awal')->nullable();
                $table->date('periode_buku_akhir')->nullable();
                $table->integer('tahun_buku')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_pmi');
    }
};
