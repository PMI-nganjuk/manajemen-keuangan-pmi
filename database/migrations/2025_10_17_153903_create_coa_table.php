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
                $table->string('id_coa')->primary();
                $table->string('nama_akun');
                $table->string('pos_saldo');
                $table->string('pos_laporan');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('coa');
    }
};
