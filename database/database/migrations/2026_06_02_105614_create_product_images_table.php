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
        Schema::create('product_images', function (Blueprint $table) {
            $table->id('id_image');
            $table->unsignedBigInteger('id_product');
            $table->string('url_gambar', 255);
            $table->tinyInteger('is_primary')->default(0);
            $table->timestamps();

            // Relasi Foreign Key
            $table->foreign('id_product')->references('id_product')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
