<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Menampilkan halaman detail produk
     */
    public function show($nama): View
    {
        $product = Product::with(['images' => function ($query) {
            $query->orderBy('is_primary', 'desc')->orderBy('id_image', 'asc');
        }])->where('nama_product', $nama)->firstOrFail();

        $variants = \Illuminate\Support\Facades\DB::table('product_variants')
            ->join('sizes', 'product_variants.id_size', '=', 'sizes.id_size')
            ->where('id_product', $product->id_product)
            ->get();

        $totalStock = $variants->sum('stok');

        return view('detail-produk', [
            'product' => $product,
            'variants' => $variants,
            'totalStock' => $totalStock
        ]);
    }
}