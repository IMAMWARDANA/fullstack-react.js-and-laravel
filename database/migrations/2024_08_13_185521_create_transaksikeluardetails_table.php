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
        Schema::create('transaksikeluardetails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_keluar_id')->constrained()->onDelete('cascade');
            $table->foreignId('barang_id')->unsigned();
            $table->integer('awalpinjam')->nullable();
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksikeluardetails');
    }
};