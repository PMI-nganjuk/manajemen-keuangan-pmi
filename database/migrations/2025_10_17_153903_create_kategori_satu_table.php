<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kategori_satu')) {
            Schema::create('kategori_satu', function (Blueprint $table) {
                $table->id('id_kategori1');
                $table->string('nama_kategori');
                $table->unsignedBigInteger('id_coa');
                $table->foreign('id_coa')->references('id_coa')->on('coa')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_satu');
    }
};
