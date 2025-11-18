<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Run the migrations.
    public function up(): void
    {
        if (!Schema::hasTable('coa')) {
            Schema::create('coa', function (Blueprint $table) {
                $table->id('id_coa');
                $table->string('nama_akun');
                $table->string('pos_saldo');
                $table->string('pos_laporan');
                $table->timestamps();
            });
        }
    }

    // Relasi ke tabel penyesuaian
    public function penyesuaian()
    {
        return $this->hasMany(Penyesuaian::class, 'id_coa', 'id_coa');
    }


    // Reverse the migrations.
    public function down(): void
    {
        Schema::dropIfExists('coa');
    }
};
