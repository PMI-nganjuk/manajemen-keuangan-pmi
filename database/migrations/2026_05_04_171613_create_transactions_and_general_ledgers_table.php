<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the old tables
        Schema::dropIfExists('gl');
        Schema::dropIfExists('penerimaan_kas');
        Schema::dropIfExists('pengeluaran_kas');

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->string('document_number')->unique(); // BKMUDD001 / BKKUDD001
            $table->enum('type', ['IN', 'OUT']);
            
            $table->unsignedBigInteger('id_program_kerja')->nullable();
            $table->foreign('id_program_kerja')->references('id_program_kerja')->on('program_kerja')->onDelete('set null');
            
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users');
            
            $table->string('cash_account_id');
            $table->foreign('cash_account_id')->references('id')->on('chart_of_accounts');
            
            $table->string('transaction_account_id');
            $table->foreign('transaction_account_id')->references('id')->on('chart_of_accounts');
            
            $table->string('reference')->nullable();
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('general_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            
            $table->string('account_id');
            $table->foreign('account_id')->references('id')->on('chart_of_accounts');
            
            $table->date('transaction_date'); 
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->text('description')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions_and_general_ledgers');
    }
};
