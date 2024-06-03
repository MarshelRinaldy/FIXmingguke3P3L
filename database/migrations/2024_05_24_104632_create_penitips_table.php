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
        Schema::create('penitips', function (Blueprint $table) {
            $table->id();
            // nama, alamat,no_ktp, no_telp, mulai_kontrak, akhir_kontrak, status
            $table->string('nama');
            $table->string('alamat');
            $table->string('no_ktp');
            $table->string('no_telp');
            $table->date('mulai_kontrak');
            $table->date('akhir_kontrak');
            $table->enum('status', ['aktif', 'tidak aktif']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penitips');
    }
};
