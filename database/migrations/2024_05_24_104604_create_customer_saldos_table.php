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
        Schema::create('customer_saldos', function (Blueprint $table) {
            $table->id();
            //user_id, jenis_transaksi, jumlah, saldo, keterangan, status
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('jenis_transaksi');
            $table->integer('jumlah');
            $table->integer('saldo');
            $table->string('keterangan');
            $table->enum('status', ['pending', 'success', 'failed']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_saldos');
    }
};
