<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kategori_dua')) {
            Schema::create('kategori_dua', function (Blueprint $table) {
                $table->id('id_kategori2');
                $table->string('nama_kategori');
                $table->unsignedBigInteger('id_kategori1');
                $table->string('id_coa');
                $table->timestamps();

                // Relasi kategori satu
                $table->foreign('id_kategori1')
                    ->references('id_kategori1')
                    ->on('kategori_satu')
                    ->onDelete('cascade');

                // Relasi COA
                $table->foreign('id_coa')
                    ->references('id_coa')
                    ->on('coa')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_dua');
    }
};
