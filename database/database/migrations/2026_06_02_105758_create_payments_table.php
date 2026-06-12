<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('id_payment');
            $table->unsignedBigInteger('id_order');
            $table->string('metode_pembayaran', 50);
            $table->string('status_pembayaran', 50);
            $table->decimal('jumlah_bayar', 10, 2);
            $table->dateTime('waktu_transaksi');
            $table->string('bukti_pembayaran', 255)->nullable(); 
            $table->timestamps();

            $table->foreign('id_order')->references('id_order')->on('orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
