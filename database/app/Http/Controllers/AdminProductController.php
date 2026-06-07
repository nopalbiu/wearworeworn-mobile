<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = Product::with(['images', 'variants'])
            ->when($search, function ($query, $search) {
                return $query->where('nama_product', 'like', '%' . $search . '%');
            })
            ->paginate(20)
            ->withQueryString();

        return view('admin.katalog', compact('products', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_product' => 'required|string|max:255|unique:products,nama_product',
            'deskripsi'    => 'required|string',
            'harga'        => 'required|numeric',
            'foto_utama'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_tambahan.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama_product.unique' => 'Nama produk ini sudah digunakan, silakan pilih nama lain.'
        ]);

        DB::beginTransaction();
        try {
            $product = Product::create([
                'nama_product' => $request->nama_product,
                'deskripsi'    => $request->deskripsi,
                'harga'        => $request->harga,
            ]);

            $pathUtama = $request->file('foto_utama')->store('products', 'public');
            ProductImage::create([
                'id_product' => $product->id_product,
                'url_gambar' => $pathUtama,
                'is_primary' => 1
            ]);

            if ($request->hasFile('foto_tambahan')) {
                foreach ($request->file('foto_tambahan') as $file) {
                    $pathTambahan = $file->store('products', 'public');
                    ProductImage::create([
                        'id_product' => $product->id_product,
                        'url_gambar' => $pathTambahan,
                        'is_primary' => 0
                    ]);
                }
            }

            if ($request->has('is_multi_size')) {
                foreach ($request->stok_ukuran as $id_size => $stok) {
                    if ($stok != null) {
                        DB::table('product_variants')->insert([
                            'id_product' => $product->id_product,
                            'id_size'    => $id_size,
                            'stok'       => $stok
                        ]);
                    }
                }
            } else {
                DB::table('product_variants')->insert([
                    'id_product' => $product->id_product,
                    'id_size'    => 1, 
                    'stok'       => $request->stok_tunggal
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_product' => 'required|string|max:255|unique:products,nama_product,' . $id . ',id_product',
            'deskripsi'    => 'required|string',
            'harga'        => 'required|numeric',
        ], [
            'nama_product.unique' => 'Nama produk ini sudah digunakan oleh produk lain.'
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'nama_product' => $request->nama_product,
            'deskripsi'    => $request->deskripsi,
            'harga'        => $request->harga,
        ]);

        if ($request->hasFile('foto_utama')) {
            $oldUtama = ProductImage::where('id_product', $id)->where('is_primary', 1)->first();
            if ($oldUtama) {
                Storage::disk('public')->delete($oldUtama->url_gambar);
                $oldUtama->delete();
            }
            ProductImage::create([
                'id_product' => $id,
                'url_gambar' => $request->file('foto_utama')->store('products', 'public'),
                'is_primary' => 1
            ]);
        }

        if ($request->hasFile('foto_tambahan')) {
            $oldTambahan = ProductImage::where('id_product', $id)->where('is_primary', 0)->get();
            foreach ($oldTambahan as $ot) {
                Storage::disk('public')->delete($ot->url_gambar);
                $ot->delete();
            }
            foreach ($request->file('foto_tambahan') as $file) {
                ProductImage::create([
                    'id_product' => $id,
                    'url_gambar' => $file->store('products', 'public'),
                    'is_primary' => 0
                ]);
            }
        }

        DB::table('product_variants')->where('id_product', $id)->delete();
        
        if ($request->has('is_multi_size')) {
            foreach ($request->stok_ukuran as $id_size => $stok) {
                if ($stok !== null) {
                    DB::table('product_variants')->insert([
                        'id_product' => $id,
                        'id_size'    => $id_size,
                        'stok'       => $stok
                    ]);
                }
            }
        } else {
            DB::table('product_variants')->insert([
                'id_product' => $id,
                'id_size'    => 1, 
                'stok'       => $request->stok_tunggal
            ]);
        }

        return redirect()->back()->with('success', 'Data produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->url_gambar);
        }
        
        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }
}