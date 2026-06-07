<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Size;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $sizes = Size::all();

        $query = Product::with(['images' => function($q) {
            $q->where('is_primary', 1);
        }]);

        // 1. Filter Pencarian Nama
        if ($request->filled('search')) {
            $query->where('nama_product', 'like', '%' . $request->search . '%');
        }

        // 2. Filter Kategori
        if ($request->filled('category')) {
            $query->whereIn('id_product', function($q) use ($request) {
                $q->select('id_product')
                  ->from('product_category')
                  ->whereIn('id_category', $request->category);
            });
        }

        // 3. Filter Ukuran
        if ($request->filled('size')) {
            $query->whereIn('id_product', function($q) use ($request) {
                $q->select('id_product')
                  ->from('product_variants')
                  ->whereIn('id_size', $request->size)
                  ->where('stok', '>', 0);
            });
        }

        // 4. Filter Rentang Harga
        if ($request->filled('price')) {
            $query->where(function($q) use ($request) {
                foreach ($request->price as $range) {
                    $parts = explode('-', $range);
                    if (count($parts) == 2) {
                        $q->orWhereBetween('harga', [$parts[0], $parts[1]]);
                    }
                }
            });
        }

        // 5. Fitur Urutkan
        if ($request->filled('sort')) {
            if ($request->sort == 'termurah') {
                $query->orderBy('harga', 'asc');
            } elseif ($request->sort == 'termahal') {
                $query->orderBy('harga', 'desc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        // Potong 16 item per halaman
        $products = $query->paginate(16)->withQueryString();

        return view('home', compact('products', 'categories', 'sizes'));
    }
}
