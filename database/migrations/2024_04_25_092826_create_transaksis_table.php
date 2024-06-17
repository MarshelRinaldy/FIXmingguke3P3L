<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
            $table->string('metode_pembayaran');
            $table->date('tanggal_transaksi');
            $table->integer('jumlah_transaksi');
            $table->string('bukti_pembayaran');
            $table->string('status_pengantaran'); 
            $table->string('jenis_delivery')->nullable(); 
            $table->float('jarak_delivery')->default(0); 
            $table->string('alamat_pengantaran')->nullable();
            $table->integer('biaya_ongkir')->default(0); 
            $table->integer('total_harga'); 
            $table->string('status_transaksi'); 
            $table->string('status_pembayaran')->default('belum bayar'); 
            $table->string('image_bukti_pembayaran')->nullable();
            $table->string('no_transaksi')->unique(); 
            $table->timestamps();
        });
    }

    /** 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
