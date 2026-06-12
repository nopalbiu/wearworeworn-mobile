<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Perluas semua kolom decimal yang masih (10,2) menjadi (15,2)
     * agar mendukung nilai harga hingga 9.999.999.999.999,99
     *
     * Tabel yang diperbaiki:
     * - orders.total_harga
     * - order_items.harga_satuan
     * - payments.jumlah_bayar
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total_harga', 15, 2)->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('harga_satuan', 15, 2)->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('jumlah_bayar', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total_harga', 10, 2)->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('harga_satuan', 10, 2)->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('jumlah_bayar', 10, 2)->change();
        });
    }
};
