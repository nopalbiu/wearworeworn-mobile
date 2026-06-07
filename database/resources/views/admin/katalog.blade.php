<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Katalog - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground min-h-screen font-sans">

    <nav class="bg-gray-300 border-b border-gray-400 px-6 py-3 flex justify-between items-center">
        <div class="flex items-center gap-6">
            <div class="bg-white text-black font-bold text-xl px-3 py-1 uppercase tracking-wider border border-gray-400">LOGO</div>
            <span class="text-gray-800 text-lg">admin</span>
        </div>
        <div class="flex">
            <a href="{{ route('admin.product.index') }}" class="bg-white text-black font-bold px-8 py-2 border border-gray-400 shadow-sm">PRODUCT</a>
            <a href="#" class="text-gray-600 font-semibold px-8 py-2 hover:bg-gray-200 transition">ORDERS</a>
        </div>
        <button class="bg-white text-black font-bold px-4 py-1 text-sm border border-gray-400 shadow-sm hover:bg-gray-100 transition">LOG OUT</button>
    </nav>

    <main class="p-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-300 p-8 min-h-[70vh]">
            
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-black">Daftar Produk</h1>
                
                <div class="flex gap-4">
                    <form action="{{ route('admin.product.index') }}" method="GET" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..." class="bg-[#F3F4F6] p-2 rounded-l-lg text-black outline-none border border-gray-300 focus:border-gray-400">
                        <button type="submit" class="bg-[#555555] hover:bg-[#333333] text-white px-4 rounded-r-lg font-bold transition">Cari</button>
                    </form>
                    <button onclick="openModal('modal-tambah')" class="bg-[#E5E7EB] hover:bg-gray-300 text-black font-bold py-2 px-6 rounded text-sm transition">
                        + Tambah Produk
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4 font-semibold">{{ session('success') }}</div>
            @endif
            @if(session('error') || $errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4 font-semibold">Gagal memproses data. Periksa kembali isian form Anda.</div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#555555] text-white text-sm">
                            <th class="p-4 font-semibold border-r border-[#666]">ID</th>
                            <th class="p-4 font-semibold border-r border-[#666]">Foto Utama</th>
                            <th class="p-4 font-semibold border-r border-[#666]">Nama</th>
                            <th class="p-4 font-semibold border-r border-[#666]">Deskripsi</th>
                            <th class="p-4 font-semibold border-r border-[#666]">Harga</th>
                            <th class="p-4 font-semibold border-r border-[#666]">Total Stok</th>
                            <th class="p-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-300 text-sm">
                        @forelse($products as $product)
                        <tr class="bg-[#9CA3AF] text-white border-b border-white hover:bg-[#8b929e] transition">
                            <td class="p-4 border-r border-white">{{ $product->id_product }}</td>
                            <td class="p-4 border-r border-white">
                                @php
                                    $fotoUtama = $product->images->where('is_primary', 1)->first();
                                    $urlFoto = $fotoUtama ? asset('storage/' . $fotoUtama->url_gambar) : 'https://dummyimage.com/50x50/374151/fff&text=No+Img';
                                @endphp
                                <img src="{{ $urlFoto }}" class="w-12 h-12 object-cover rounded">
                            </td>
                            <td class="p-4 border-r border-white font-semibold">{{ $product->nama_product }}</td>
                            <td class="p-4 border-r border-white text-xs truncate max-w-[150px]">{{ $product->deskripsi }}</td>
                            <td class="p-4 border-r border-white">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                            <td class="p-4 border-r border-white font-bold">{{ $product->variants->sum('stok') }}</td>
                            <td class="p-4 text-center space-x-2">
                                <button onclick="openEditModal({{ $product }})" class="text-white hover:text-blue-300" title="Edit">✏️</button>
                                
                                <form action="{{ route('admin.product.destroy', $product->id_product) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-white hover:text-red-300" title="Hapus">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr class="bg-[#9CA3AF] text-white">
                            <td colspan="7" class="p-8 text-center font-semibold text-lg">Belum ada data produk di database.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>

        </div>
    </main>

    <div id="modal-tambah" class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-center justify-center z-50 overflow-y-auto pt-10 pb-10">
        <div class="bg-white w-full max-w-lg rounded-2xl p-8 shadow-2xl my-auto">
            <h2 class="text-2xl font-bold text-black mb-6">Tambah Produk Baru</h2>

            <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                @csrf
                
                <div class="flex flex-col gap-1">
                    <label class="font-bold text-black text-sm">Nama Produk</label>
                    <input type="text" name="nama_product" required class="bg-[#F3F4F6] p-3 rounded-lg text-black outline-none focus:ring-2 focus:ring-gray-300">
                </div>

                <div class="flex flex-col gap-1">
                    <label class="font-bold text-black text-sm">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" required class="bg-[#F3F4F6] p-3 rounded-lg text-black outline-none focus:ring-2 focus:ring-gray-300"></textarea>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="font-bold text-black text-sm">Harga (Rp)</label>
                    <input type="number" name="harga" required class="bg-[#F3F4F6] p-3 rounded-lg text-black outline-none focus:ring-2 focus:ring-gray-300">
                </div>

                <div class="flex flex-col gap-1 border-t border-b border-gray-200 py-3 my-2">
                    <label class="font-bold text-black text-sm text-blue-600">Foto Utama (Wajib)</label>
                    <input type="file" name="foto_utama" accept="image/*" required class="text-black text-sm mb-3">

                    <label class="font-bold text-black text-sm text-gray-600">Foto Tambahan (Opsional, bisa >1)</label>
                    <input type="file" name="foto_tambahan[]" accept="image/*" multiple class="text-black text-sm">
                </div>

                <div class="flex justify-between items-center bg-gray-100 p-3 rounded-lg">
                    <label class="text-black text-sm font-bold cursor-pointer" for="add-multi-size">Aktifkan Multi Size Item</label>
                    <input type="checkbox" id="add-multi-size" name="is_multi_size" value="1" class="w-5 h-5 cursor-pointer accent-black" onchange="toggleSize('add')">
                </div>

                <div id="add-stok-tunggal" class="flex flex-col gap-1 block">
                    <label class="font-bold text-black text-sm">Total Stok (All Size)</label>
                    <input type="number" name="stok_tunggal" class="bg-[#F3F4F6] p-3 rounded-lg text-black outline-none focus:ring-2 focus:ring-gray-300">
                </div>

                <div id="add-stok-multi" class="hidden flex-col gap-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <label class="font-bold text-black text-sm mb-1">Stok Per Ukuran:</label>
                    <div class="flex items-center gap-3"><span class="w-8 font-bold text-black">S</span> <input type="number" name="stok_ukuran[2]" class="w-full bg-white border border-gray-300 p-2 text-black rounded"></div>
                    <div class="flex items-center gap-3"><span class="w-8 font-bold text-black">M</span> <input type="number" name="stok_ukuran[3]" class="w-full bg-white border border-gray-300 p-2 text-black rounded"></div>
                    <div class="flex items-center gap-3"><span class="w-8 font-bold text-black">L</span> <input type="number" name="stok_ukuran[4]" class="w-full bg-white border border-gray-300 p-2 text-black rounded"></div>
                    <div class="flex items-center gap-3"><span class="w-8 font-bold text-black">XL</span> <input type="number" name="stok_ukuran[5]" class="w-full bg-white border border-gray-300 p-2 text-black rounded"></div>
                </div>

                <div class="flex justify-between gap-4 mt-4">
                    <button type="button" onclick="closeModal('modal-tambah')" class="w-1/2 bg-[#555555] hover:bg-[#333333] text-white font-bold py-3 rounded-lg transition">Batal</button>
                    <button type="submit" class="w-1/2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-edit" class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-center justify-center z-50 overflow-y-auto pt-10 pb-10">
        <div class="bg-white w-full max-w-lg rounded-2xl p-8 shadow-2xl my-auto">
            <h2 class="text-2xl font-bold text-black mb-6">Edit Data Produk</h2>
            
            <form id="form-edit" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                @csrf
                @method('PUT')
                
                <div class="flex flex-col gap-1">
                    <label class="font-bold text-black text-sm">Nama Produk</label>
                    <input type="text" id="edit-nama" name="nama_product" required class="bg-[#F3F4F6] p-3 rounded-lg text-black outline-none focus:ring-2 focus:ring-gray-300">
                </div>
                
                <div class="flex flex-col gap-1">
                    <label class="font-bold text-black text-sm">Deskripsi</label>
                    <textarea id="edit-deskripsi" name="deskripsi" rows="3" required class="bg-[#F3F4F6] p-3 rounded-lg text-black outline-none focus:ring-2 focus:ring-gray-300"></textarea>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="font-bold text-black text-sm">Harga (Rp)</label>
                    <input type="number" id="edit-harga" name="harga" required class="bg-[#F3F4F6] p-3 rounded-lg text-black outline-none focus:ring-2 focus:ring-gray-300">
                </div>

                <div class="flex flex-col gap-1 border-t border-b border-gray-200 py-3 my-2">
                    <label class="font-bold text-black text-sm text-blue-600">Ganti Foto Utama <span class="text-xs font-normal text-gray-500">(Kosongkan jika tidak diganti)</span></label>
                    <input type="file" name="foto_utama" accept="image/*" class="text-black text-sm mb-3">

                    <label class="font-bold text-black text-sm text-gray-600">Ganti Foto Tambahan <span class="text-xs font-normal text-gray-500">(Kosongkan jika tidak diganti)</span></label>
                    <input type="file" name="foto_tambahan[]" accept="image/*" multiple class="text-black text-sm">
                </div>

                <div class="flex justify-between items-center bg-gray-100 p-3 rounded-lg">
                    <label class="text-black text-sm font-bold cursor-pointer" for="edit-multi-size">Aktifkan Multi Size Item</label>
                    <input type="checkbox" id="edit-multi-size" name="is_multi_size" value="1" class="w-5 h-5 cursor-pointer accent-black" onchange="toggleSize('edit')">
                </div>

                <div id="edit-stok-tunggal" class="flex flex-col gap-1 block">
                    <label class="font-bold text-black text-sm">Total Stok (All Size)</label>
                    <input type="number" id="edit-stok-tunggal-input" name="stok_tunggal" class="bg-[#F3F4F6] p-3 rounded-lg text-black outline-none focus:ring-2 focus:ring-gray-300">
                </div>

                <div id="edit-stok-multi" class="hidden flex-col gap-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <label class="font-bold text-black text-sm mb-1">Stok Per Ukuran:</label>
                    <div class="flex items-center gap-3"><span class="w-8 font-bold text-black">S</span> <input type="number" id="edit-stok-ukuran-2" name="stok_ukuran[2]" class="edit-stok-ukuran w-full bg-white border border-gray-300 p-2 text-black rounded"></div>
                    <div class="flex items-center gap-3"><span class="w-8 font-bold text-black">M</span> <input type="number" id="edit-stok-ukuran-3" name="stok_ukuran[3]" class="edit-stok-ukuran w-full bg-white border border-gray-300 p-2 text-black rounded"></div>
                    <div class="flex items-center gap-3"><span class="w-8 font-bold text-black">L</span> <input type="number" id="edit-stok-ukuran-4" name="stok_ukuran[4]" class="edit-stok-ukuran w-full bg-white border border-gray-300 p-2 text-black rounded"></div>
                    <div class="flex items-center gap-3"><span class="w-8 font-bold text-black">XL</span> <input type="number" id="edit-stok-ukuran-5" name="stok_ukuran[5]" class="edit-stok-ukuran w-full bg-white border border-gray-300 p-2 text-black rounded"></div>
                </div>

                <div class="flex justify-between gap-4 mt-4">
                    <button type="button" onclick="closeModal('modal-edit')" class="w-1/2 bg-[#555555] hover:bg-[#333333] text-white font-bold py-3 rounded-lg transition">Batal</button>
                    <button type="submit" class="w-1/2 bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function openEditModal(product) {
            document.getElementById('edit-nama').value = product.nama_product;
            document.getElementById('edit-deskripsi').value = product.deskripsi;
            document.getElementById('edit-harga').value = product.harga;
            
            document.getElementById('form-edit').action = '/admin/products/' + product.id_product;

            document.getElementById('edit-stok-tunggal-input').value = '';
            document.querySelectorAll('.edit-stok-ukuran').forEach(input => input.value = '');

            let isMulti = false;
            if (product.variants && product.variants.length > 0) {
                product.variants.forEach(v => {
                    if (v.id_size > 1) isMulti = true;
                });

                if (isMulti) {
                    document.getElementById('edit-multi-size').checked = true;
                    product.variants.forEach(v => {
                        let input = document.getElementById('edit-stok-ukuran-' + v.id_size);
                        if(input) input.value = v.stok;
                    });
                } else {
                    document.getElementById('edit-multi-size').checked = false;
                    document.getElementById('edit-stok-tunggal-input').value = product.variants[0].stok;
                }
            }

            toggleSize('edit');
            openModal('modal-edit');
        }

        function toggleSize(tipe) {
            const checkbox = document.getElementById(tipe + '-multi-size');
            const stokTunggal = document.getElementById(tipe + '-stok-tunggal');
            const stokMulti = document.getElementById(tipe + '-stok-multi');

            if (checkbox.checked) {
                stokTunggal.classList.add('hidden');
                stokTunggal.classList.remove('block');
                stokMulti.classList.remove('hidden');
                stokMulti.classList.add('flex');
            } else {
                stokTunggal.classList.remove('hidden');
                stokTunggal.classList.add('block');
                stokMulti.classList.add('hidden');
                stokMulti.classList.remove('flex');
            }
        }
    </script>
</body>
</html>