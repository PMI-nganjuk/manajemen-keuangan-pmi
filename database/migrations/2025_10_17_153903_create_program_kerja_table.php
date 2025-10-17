<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('program_kerja')) {
            Schema::create('program_kerja', function (Blueprint $table) {
                $table->id('id_program_kerja');
                $table->string('nama_program');
                $table->string('keterangan')->nullable();
                $table->unsignedBigInteger('id_pegawai')->nullable();
                $table->foreign('id_pegawai')->references('id_user')->on('users')->onDelete('set null');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('program_kerja');
    }
};
