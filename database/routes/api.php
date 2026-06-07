<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rute untuk aplikasi Android pelanggan/guest menembak data produk
Route::get('/products', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Data produk katalog berhasil diambil',
        'data' => [
            [
                'id_product' => 1, 
                'nama_product' => 'Jaket Denim Original', 
                'harga' => 250000,
                'stok' => 15
            ],
            [
                'id_product' => 2, 
                'nama_product' => 'Kaos Oversize Hitam', 
                'harga' => 85000,
                'stok' => 40
            ]
        ]
    ]);
});