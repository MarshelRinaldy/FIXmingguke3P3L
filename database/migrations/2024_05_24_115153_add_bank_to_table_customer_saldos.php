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
        Schema::table('customer_saldos', function (Blueprint $table) {
            //bank, norek, nama
            $table->string('bank')->nullable();
            $table->string('norek')->nullable();
            $table->string('nama')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_saldos', function (Blueprint $table) {
            $table->dropColumn('bank');
            $table->dropColumn('norek');
            $table->dropColumn('nama');
        });
    }
};
