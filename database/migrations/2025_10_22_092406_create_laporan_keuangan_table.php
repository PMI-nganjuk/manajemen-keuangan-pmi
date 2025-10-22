<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_keuangan', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->integer('kas_tahun1');
            $table->integer('kas_tahun2');
            $table->integer('saldo_akhir');
            $table->timestamps();
        });

        if (Schema::hasTable('penyesuaian') && Schema::hasColumn('penyesuaian', 'id_laporan')) {
            Schema::table('penyesuaian', function (Blueprint $table) {
                $table->foreign('id_laporan')->references('id_laporan')->on('laporan_keuangan')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('penyesuaian') && Schema::hasColumn('penyesuaian', 'id_laporan')) {
            Schema::table('penyesuaian', function (Blueprint $table) {
                $table->dropForeign(['id_laporan']);
            });
        }

        Schema::dropIfExists('laporan_keuangan');
    }
};
