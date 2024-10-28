<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_keluars', function (Blueprint $table) {
            $table->id();
            $table->integer('notransaksi');
            $table->string('namainstansi');
            $table->foreignId('keperluan_id')->unsigned();
            $table->string('alasan', 1000);  // Ensure this line is correct
            $table->dateTime('tanggalinput');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_keluars');
    }
};