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
        Schema::create('transaksimasukdetails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_masuk_id')->constrained()->onDelete('cascade');
            $table->integer('jumlah');
            $table->foreignId('barang_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksimasukdetails');
    }
};
