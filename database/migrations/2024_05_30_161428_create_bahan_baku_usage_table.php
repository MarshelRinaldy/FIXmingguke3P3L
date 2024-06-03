<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBahanBakuUsageTable extends Migration
{
    public function up()
    {
        Schema::create('bahan_baku_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku')->onDelete('cascade');
            $table->foreignId('transaksi_id')->nullable()->constrained('transaksis')->onDelete('cascade');
            $table->date('tanggal_transaksi');
            $table->integer('jumlah_digunakan')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bahan_baku_usage');
    }
}


