<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Cloudinary\Cloudinary; // Memanggil library Cloudinary

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Mengambil semua data kategori untuk ditampilkan di dropdown modal
        $categories = DB::table('categories')->get();

        // Menambahkan 'categories' ke dalam relasi yang dipanggil
        $products = Product::with(['images', 'variants', 'categories'])
            ->when($search, function ($query, $search) {
                return $query->where('nama_product', 'like', '%' . $search . '%');
            })
            ->paginate(20)
            ->withQueryString();

        // Mengirimkan $categories ke view
        return view('admin.products.index', compact('products', 'search', 'categories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_product' => 'required|string|max:255|unique:products,nama_product',
            'id_category'  => 'required', // Validasi kategori wajib diisi
            'deskripsi'    => 'required|string',
            'harga'        => 'required|numeric|min:0|max:9999999999999',
            'foto_utama'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_tambahan.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        if ($request->has('is_multi_size')) {
            $rules['stok_ukuran'] = 'required|array|min:1';
            $rules['stok_ukuran.*'] = 'nullable|integer|min:0';
        } else {
            $rules['stok_tunggal'] = 'required|integer|min:0';
        }

        $messages = [
            'nama_product.required'  => '⚠️ Nama produk wajib diisi.',
            'nama_product.unique'    => '⚠️ Nama produk "' . $request->nama_product . '" sudah digunakan, pilih nama lain.',
            'nama_product.max'       => '⚠️ Nama produk maksimal 255 karakter.',
            'id_category.required'   => '⚠️ Kategori produk wajib dipilih.',
            'deskripsi.required'     => '⚠️ Deskripsi produk wajib diisi.',
            'harga.required'         => '⚠️ Harga produk wajib diisi.',
            'harga.numeric'          => '⚠️ Harga harus berupa angka.',
            'harga.min'              => '⚠️ Harga tidak boleh negatif.',
            'harga.max'              => '⚠️ Harga terlalu besar! Maksimal harga adalah Rp 9.999.999.999.999.',
            'foto_utama.required'    => '📷 Foto utama wajib diunggah. Silakan pilih gambar produk.',
            'foto_utama.image'       => '📷 File foto utama harus berupa gambar (jpg, png, jpeg).',
            'foto_utama.mimes'       => '📷 Foto utama hanya boleh berformat: jpeg, png, jpg.',
            'foto_utama.max'         => '📷 Ukuran foto utama maksimal 2MB.',
            'foto_tambahan.*.image'  => '📷 Salah satu foto tambahan bukan file gambar yang valid.',
            'foto_tambahan.*.mimes'  => '📷 Foto tambahan hanya boleh berformat: jpeg, png, jpg.',
            'foto_tambahan.*.max'    => '📷 Ukuran setiap foto tambahan maksimal 2MB.',
            'stok_tunggal.required'  => '📦 Stok produk wajib diisi. Masukkan jumlah stok tersedia.',
            'stok_tunggal.integer'   => '📦 Stok harus berupa angka bulat.',
            'stok_tunggal.min'       => '📦 Stok tidak boleh negatif.',
            'stok_ukuran.required'   => '📦 Minimal isi stok untuk satu ukuran.',
            'stok_ukuran.*.integer'  => '📦 Stok per ukuran harus berupa angka bulat.',
            'stok_ukuran.*.min'      => '📦 Stok per ukuran tidak boleh negatif.',
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messages);

        if ($request->has('is_multi_size')) {
            $validator->after(function ($validator) use ($request) {
                $stokUkuran = $request->input('stok_ukuran', []);
                $adaStok = false;
                foreach ($stokUkuran as $stok) {
                    if ($stok !== null && $stok !== '') {
                        $adaStok = true;
                        break;
                    }
                }
                if (!$adaStok) {
                    // Update pesan error agar menyertakan XXL
                    $validator->errors()->add('stok_ukuran', '📦 Minimal isi stok untuk setidaknya satu ukuran (S, M, L, XL, atau XXL).');
                }
            });
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // 1. Simpan Data Produk Utama
            $product = Product::create([
                'nama_product' => $request->nama_product,
                'deskripsi'    => $request->deskripsi,
                'harga'        => $request->harga,
            ]);

            // 2. Simpan Relasi Kategori ke tabel product_category
            DB::table('product_category')->insert([
                'id_product'  => $product->id_product,
                'id_category' => $request->id_category,
            ]);

            // ==========================================
            // LOGIKA UPLOAD CLOUDINARY (STORE)
            // ==========================================
            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));

            // Upload Foto Utama
            $uploadUtama = $cloudinary->uploadApi()->upload($request->file('foto_utama')->getRealPath(), [
                'folder' => 'wearworeworn_products'
            ]);
            
            ProductImage::create([
                'id_product' => $product->id_product,
                'url_gambar' => $uploadUtama['secure_url'],
                'is_primary' => 1
            ]);

            // Upload Foto Tambahan (Jika Ada)
            if ($request->hasFile('foto_tambahan')) {
                foreach ($request->file('foto_tambahan') as $file) {
                    $uploadTambahan = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                        'folder' => 'wearworeworn_products'
                    ]);
                    
                    ProductImage::create([
                        'id_product' => $product->id_product,
                        'url_gambar' => $uploadTambahan['secure_url'],
                        'is_primary' => 0
                    ]);
                }
            }
            // ==========================================

            // 3. Simpan Varian Stok
            if ($request->has('is_multi_size')) {
                foreach ($request->stok_ukuran as $id_size => $stok) {
                    if ($stok !== null && $stok !== '') {
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
            return redirect()->back()
                ->withInput()
                ->withErrors(['store_error' => '❌ Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_product' => 'required|string|max:255|unique:products,nama_product,' . $id . ',id_product',
            'id_category'  => 'required', // Validasi kategori
            'deskripsi'    => 'required|string',
            'harga'        => 'required|numeric',
        ], [
            'nama_product.unique' => 'Nama produk ini sudah digunakan oleh produk lain.',
            'id_category.required' => 'Kategori produk wajib dipilih.'
        ]);

        $product = Product::findOrFail($id);

        // 1. Update Data Produk Utama
        $product->update([
            'nama_product' => $request->nama_product,
            'deskripsi'    => $request->deskripsi,
            'harga'        => $request->harga,
        ]);

        // 2. Update Relasi Kategori (Gunakan updateOrInsert agar aman jika belum ada sebelumnya)
        DB::table('product_category')->updateOrInsert(
            ['id_product' => $id],
            ['id_category' => $request->id_category]
        );

        // ==========================================
        // LOGIKA UPLOAD CLOUDINARY (UPDATE)
        // ==========================================
        $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));

        if ($request->hasFile('foto_utama')) {
            $oldUtama = ProductImage::where('id_product', $id)->where('is_primary', 1)->first();
            if ($oldUtama) {
                $oldUtama->delete();
            }
            
            $uploadUtama = $cloudinary->uploadApi()->upload($request->file('foto_utama')->getRealPath(), [
                'folder' => 'wearworeworn_products'
            ]);

            ProductImage::create([
                'id_product' => $id,
                'url_gambar' => $uploadUtama['secure_url'],
                'is_primary' => 1
            ]);
        }

        if ($request->hasFile('foto_tambahan')) {
            $oldTambahan = ProductImage::where('id_product', $id)->where('is_primary', 0)->get();
            foreach ($oldTambahan as $ot) {
                $ot->delete(); 
            }
            
            foreach ($request->file('foto_tambahan') as $file) {
                $uploadTambahan = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                    'folder' => 'wearworeworn_products'
                ]);

                ProductImage::create([
                    'id_product' => $id,
                    'url_gambar' => $uploadTambahan['secure_url'],
                    'is_primary' => 0
                ]);
            }
        }
        // ==========================================

        // 3. Update Varian Stok
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
        
        // Hapus relasi kategori terlebih dahulu sebelum produk dihapus
        DB::table('product_category')->where('id_product', $id)->delete();
        
        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }
}