<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Run the migrations.
    public function up(): void
    {
        if (!Schema::hasTable('kategori_satu')) {
            Schema::create('kategori_satu', function (Blueprint $table) {
                $table->id('id_kategori1');
                $table->string('nama_kategori');
                $table->timestamps();
            });
        }
    }

    // Reverse the migrations.
    public function down(): void
    {
        Schema::dropIfExists('kategori_satu');
    }
};
