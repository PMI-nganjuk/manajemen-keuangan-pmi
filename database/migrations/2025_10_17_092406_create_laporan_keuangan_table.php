<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('laporan_keuangan')) {
            Schema::create('laporan_keuangan', function (Blueprint $table) {
                $table->id('id_laporan');
                $table->integer('kas_tahun1')->default(0);
                $table->integer('kas_tahun2')->default(0);
                $table->integer('saldo_akhir')->default(0);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('penyesuaian') && !Schema::hasColumn('penyesuaian', 'id_laporan')) {
            Schema::table('penyesuaian', function (Blueprint $table) {
                $table->unsignedBigInteger('id_laporan')->nullable()->after('id_program_kerja');
                $table->foreign('id_laporan')
                    ->references('id_laporan')
                    ->on('laporan_keuangan')
                    ->onDelete('cascade');
            });
        }

        foreach (['pengeluaran_kas', 'penerimaan_kas'] as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'id_laporan')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->unsignedBigInteger('id_laporan')->nullable()->after('id_program_kerja');
                    $table->foreign('id_laporan')
                        ->references('id_laporan')
                        ->on('laporan_keuangan')
                        ->onDelete('cascade');
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['penyesuaian', 'pengeluaran_kas', 'penerimaan_kas'] as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'id_laporan')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign([$table->getTable() . '_id_laporan_foreign' ?? 'id_laporan']);
                    $table->dropColumn('id_laporan');
                });
            }
        }

        Schema::dropIfExists('laporan_keuangan');
    }
};
