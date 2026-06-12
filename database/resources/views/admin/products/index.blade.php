<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Katalog - Admin | WearWoreWorn</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen font-sans antialiased selection:bg-blue-950 selection:text-blue-200">

    <nav class="bg-zinc-900/60 backdrop-blur-md border-b border-zinc-800/80 px-6 py-4 grid grid-cols-3 items-center sticky top-0 z-40">
        <div class="flex items-center gap-6 justify-start">
            <a href="/" class="flex items-center transition-transform duration-300 hover:scale-105 relative z-10">
                <img src="{{ asset('images/logo-www.png') }}" alt="WearWoreWorn Logo" class="h-8 w-auto object-contain">
            </a>
            <span class="text-blue-400 text-xs font-bold uppercase tracking-[0.2em] bg-blue-950/40 px-2.5 py-1 rounded border border-blue-900/30">Admin Panel</span>
        </div>
        
        <div class="flex justify-center gap-2 w-full">
            <a href="{{ route('admin.product.index') }}" class="bg-white text-zinc-950 font-black px-6 py-2 rounded-lg text-xs tracking-wider uppercase transition-all shadow-[0_0_15px_rgba(255,255,255,0.1)]">PRODUCT</a>    
            <a href="{{ route('admin.orders.index') }}" class="text-zinc-400 hover:text-white hover:bg-zinc-800/60 font-bold px-6 py-2 rounded-lg text-xs tracking-wider uppercase transition-all">ORDERS</a>    
        </div>

        <div class="flex justify-end">
            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                @csrf
                <button type="submit" class="bg-transparent border border-zinc-800 hover:border-zinc-700 text-zinc-400 hover:text-white font-bold px-4 py-2 text-xs tracking-wider rounded-lg transition-all uppercase">LOG OUT</button>
            </form>
        </div>
    </nav>

    <main class="p-8 max-w-7xl mx-auto w-full">
        <div class="bg-zinc-900/30 backdrop-blur-md rounded-2xl border border-zinc-800/80 p-8 min-h-[70vh] shadow-2xl relative overflow-hidden">
            
            <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-zinc-700/30 to-transparent"></div>
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <h1 class="text-3xl font-black text-white uppercase tracking-tight">Daftar Produk</h1>
                
                <div class="flex flex-wrap items-center gap-4 w-full sm:w-auto">
                    <form action="{{ route('admin.product.index') }}" method="GET" class="flex w-full sm:w-auto shadow-inner rounded-xl overflow-hidden border border-zinc-800/80">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..." 
                            class="bg-zinc-950/60 px-4 py-2.5 text-zinc-200 text-sm outline-none w-full sm:w-64 placeholder-zinc-600 focus:bg-zinc-950 transition-all">
                        <button type="submit" class="bg-zinc-800 hover:bg-zinc-700 text-zinc-200 px-5 font-bold text-sm transition-all border-l border-zinc-800">Cari</button>
                    </form>
                    
                    <button onclick="openModal('modal-tambah')" class="bg-white hover:bg-zinc-200 text-zinc-950 font-black py-2.5 px-6 rounded-xl text-xs uppercase tracking-wider transition-all shadow-[0_0_15px_rgba(255,255,255,0.05)] w-full sm:w-auto">
                        + Tambah Produk
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-emerald-950/30 border border-emerald-900 text-emerald-400 p-4 rounded-xl mb-6 text-sm font-medium shadow-inner flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error') || $errors->any())
                <div class="bg-red-950/30 border border-red-900 text-red-400 p-4 rounded-xl mb-6 text-sm font-medium shadow-inner">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                        <span class="font-bold">Produk gagal disimpan. Perbaiki kesalahan berikut:</span>
                    </div>
                    @if(session('error'))
                        <p class="ml-4">{{ session('error') }}</p>
                    @endif
                    @if($errors->any())
                        <ul class="ml-4 space-y-1 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            <div class="overflow-x-auto rounded-xl border border-zinc-800/60 bg-zinc-950/20 shadow-inner">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-zinc-900/80 border-b border-zinc-800 text-zinc-400 text-xs font-bold tracking-widest uppercase">
                            <th class="p-4 border-r border-zinc-800/40 w-16">ID</th>
                            <th class="p-4 border-r border-zinc-800/40 w-24">Foto Utama</th>
                            <th class="p-4 border-r border-zinc-800/40">Nama</th>
                            <th class="p-4 border-r border-zinc-800/40 max-w-xs">Deskripsi</th>
                            <th class="p-4 border-r border-zinc-800/40">Kategori</th>
                            <th class="p-4 border-r border-zinc-800/40">Harga</th>
                            <th class="p-4 border-r border-zinc-800/40 w-28">Total Stok</th>
                            <th class="p-4 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-zinc-300 text-sm divide-y divide-zinc-800/40">
                        @forelse($products as $product)
                        <tr class="bg-zinc-900/10 hover:bg-zinc-900/50 transition-all">
                            <td class="p-4 border-r border-zinc-800/40 text-zinc-500 font-mono text-xs">{{ $product->id_product }}</td>
                            <td class="p-4 border-r border-zinc-800/40">
                                @php
                                    $fotoUtama = $product->images->where('is_primary', 1)->first();
                                    if ($fotoUtama) {
                                        $urlFoto = str_starts_with($fotoUtama->url_gambar, 'http') ? $fotoUtama->url_gambar : asset('storage/' . $fotoUtama->url_gambar);
                                    } else {
                                        $urlFoto = 'https://dummyimage.com/50x50/18181b/71717a&text=No+Img';
                                    }
                                @endphp
                                <img src="{{ $urlFoto }}" class="w-12 h-12 object-cover rounded-lg ring-1 ring-zinc-800 shadow-md">
                            </td>
                            <td class="p-4 border-r border-zinc-800/40 font-bold text-white tracking-tight">{{ $product->nama_product }}</td>
                            <td class="p-4 border-r border-zinc-800/40 text-xs text-zinc-400 truncate max-w-[150px]">{{ $product->deskripsi }}</td>
                            
                            <td class="p-4 border-r border-zinc-800/40 text-xs text-zinc-300">
                                @if($product->categories && $product->categories->count() > 0)
                                    <span class="bg-zinc-800 px-2 py-1 rounded border border-zinc-700">{{ $product->categories->first()->nama_category }}</span>
                                @else
                                    <span class="text-zinc-600 italic">-</span>
                                @endif
                            </td>

                            <td class="p-4 border-r border-zinc-800/40 font-semibold text-zinc-200">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                            <td class="p-4 border-r border-zinc-800/40 font-mono font-bold text-zinc-100">{{ $product->variants->sum('stok') }}</td>
                            <td class="p-4 text-center space-x-3 whitespace-nowrap">
                                @php
                                    $catId = ($product->categories && $product->categories->count() > 0) ? $product->categories->first()->id_category : '';
                                @endphp
                                <button onclick="openEditModal({{ $product }}, '{{ $catId }}')" class="text-zinc-400 hover:text-blue-400 transition-colors text-base p-1" title="Edit">✏️</button>
                                
                                <form action="{{ route('admin.product.destroy', $product->id_product) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-zinc-400 hover:text-red-400 transition-colors text-base p-1" title="Hapus">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr class="bg-zinc-900/20">
                            <td colspan="8" class="p-12 text-center font-medium text-zinc-500 tracking-wide">Belum ada data produk di database.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 text-zinc-400 dark-pagination">
                {{ $products->links() }}
            </div>

        </div>
    </main>

    <div id="modal-tambah" class="fixed inset-0 bg-zinc-950/80 backdrop-blur-sm hidden flex items-center justify-center z-50 overflow-y-auto p-4 md:p-10">
        <div class="bg-zinc-900 border border-zinc-800 w-full max-w-lg rounded-2xl p-8 shadow-2xl relative my-auto">
            <h2 class="text-2xl font-black text-white mb-6 uppercase tracking-tight border-b border-zinc-800 pb-3">Tambah Produk Baru</h2>

            @if($errors->any() && !$errors->has('edit_mode'))
            <div class="bg-red-950/30 border border-red-800 text-red-400 p-4 rounded-xl mb-5 text-xs">
                <p class="font-bold mb-2 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span> Periksa kesalahan berikut:</p>
                <ul class="space-y-1 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
                @csrf
                
                <div class="flex flex-col gap-1.5">
                    <label class="font-bold text-zinc-400 text-xs uppercase tracking-wider">Nama Produk</label>
                    <input type="text" name="nama_product" value="{{ old('nama_product') }}" required 
                        class="bg-zinc-950/60 text-white border {{ $errors->has('nama_product') ? 'border-red-500/80' : 'border-zinc-800/80' }} rounded-xl p-3 text-sm focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all shadow-inner">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="font-bold text-zinc-400 text-xs uppercase tracking-wider">Kategori Produk</label>
                    <select name="id_category" required class="bg-zinc-950/60 text-white border {{ $errors->has('id_category') ? 'border-red-500/80' : 'border-zinc-800/80' }} rounded-xl p-3 text-sm focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all shadow-inner cursor-pointer appearance-none">
                        <option value="" disabled {{ old('id_category') ? '' : 'selected' }}>-- Pilih Kategori --</option>
                        @isset($categories)
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id_category }}" {{ old('id_category') == $cat->id_category ? 'selected' : '' }}>{{ $cat->nama_category }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="font-bold text-zinc-400 text-xs uppercase tracking-wider">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" required 
                        class="bg-zinc-950/60 text-white border {{ $errors->has('deskripsi') ? 'border-red-500/80' : 'border-zinc-800/80' }} rounded-xl p-3 text-sm focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all shadow-inner resize-none">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="font-bold text-zinc-400 text-xs uppercase tracking-wider">Harga (Rp)</label>
                    <input type="number" name="harga" value="{{ old('harga') }}" required 
                        class="bg-zinc-950/60 text-white border {{ $errors->has('harga') ? 'border-red-500/80' : 'border-zinc-800/80' }} rounded-xl p-3 text-sm focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all shadow-inner font-mono">
                </div>

                <div class="flex flex-col gap-3 border-t border-b border-zinc-800/80 py-4 my-1">
                    <div class="flex flex-col gap-1">
                        <label class="font-bold text-blue-400 text-xs uppercase tracking-wider">Foto Utama (Wajib)</label>
                        <input type="file" name="foto_utama" accept="image/*" required class="text-zinc-400 text-xs file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-zinc-800 file:text-zinc-200 hover:file:bg-zinc-700 cursor-pointer {{ $errors->has('foto_utama') ? 'ring-1 ring-red-500/50 rounded-lg' : '' }}">
                        @if($errors->has('foto_utama'))
                            <p class="text-red-400 text-[10px] mt-0.5">{{ $errors->first('foto_utama') }}</p>
                        @endif
                    </div>

                    <div class="flex flex-col gap-1 mt-2">
                        <label class="font-bold text-zinc-400 text-xs uppercase tracking-wider">Foto Tambahan <span class="text-[10px] font-normal normal-case text-zinc-500">(Opsional)</span></label>
                        <input type="file" name="foto_tambahan[]" accept="image/*" multiple class="text-zinc-400 text-xs file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-zinc-800 file:text-zinc-200 hover:file:bg-zinc-700 cursor-pointer">
                    </div>
                </div>

                <div class="flex justify-between items-center bg-zinc-950/40 border border-zinc-800/60 p-4 rounded-xl">
                    <label class="text-zinc-300 text-xs font-bold uppercase tracking-wider cursor-pointer select-none" for="add-multi-size">Aktifkan Multi Size Item</label>
                    <input type="checkbox" id="add-multi-size" name="is_multi_size" value="1" class="w-5 h-5 cursor-pointer accent-blue-500 rounded border-zinc-800 bg-zinc-950 focus:ring-0 focus:ring-offset-0" {{ old('is_multi_size') ? 'checked' : '' }} onchange="toggleSize('add')">
                </div>

                <div id="add-stok-tunggal" class="flex flex-col gap-1.5 {{ old('is_multi_size') ? 'hidden' : 'block' }}">
                    <label class="font-bold text-zinc-400 text-xs uppercase tracking-wider">Total Stok (All Size) <span class="text-red-400">*</span></label>
                    <input type="number" name="stok_tunggal" value="{{ old('stok_tunggal') }}" class="bg-zinc-950/60 text-white border {{ $errors->has('stok_tunggal') ? 'border-red-500/80' : 'border-zinc-800/80' }} rounded-xl p-3 text-sm focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all font-mono">
                    @if($errors->has('stok_tunggal'))
                        <p class="text-red-400 text-[10px] mt-0.5">{{ $errors->first('stok_tunggal') }}</p>
                    @endif
                </div>

                <div id="add-stok-multi" class="{{ old('is_multi_size') ? 'flex' : 'hidden' }} flex-col gap-2 bg-zinc-950/20 p-4 rounded-xl border {{ $errors->has('stok_ukuran') ? 'border-red-500/50' : 'border-zinc-800/60' }}">
                    <label class="font-bold text-blue-400 text-xs uppercase tracking-wider mb-2">Stok Per Ukuran: <span class="text-red-400">*</span></label>
                    @if($errors->has('stok_ukuran'))
                        <p class="text-red-400 text-[10px] -mt-1 mb-1">{{ $errors->first('stok_ukuran') }}</p>
                    @endif
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center gap-3"><span class="w-8 font-bold text-zinc-500 text-xs text-center">S</span> <input type="number" name="stok_ukuran[1]" value="{{ old('stok_ukuran.1') }}" class="w-full bg-zinc-950/60 border border-zinc-800/80 p-2 text-sm text-white font-mono rounded-lg outline-none focus:border-zinc-700"></div>
                        <div class="flex items-center gap-3"><span class="w-8 font-bold text-zinc-500 text-xs text-center">M</span> <input type="number" name="stok_ukuran[2]" value="{{ old('stok_ukuran.2') }}" class="w-full bg-zinc-950/60 border border-zinc-800/80 p-2 text-sm text-white font-mono rounded-lg outline-none focus:border-zinc-700"></div>
                        <div class="flex items-center gap-3"><span class="w-8 font-bold text-zinc-500 text-xs text-center">L</span> <input type="number" name="stok_ukuran[3]" value="{{ old('stok_ukuran.3') }}" class="w-full bg-zinc-950/60 border border-zinc-800/80 p-2 text-sm text-white font-mono rounded-lg outline-none focus:border-zinc-700"></div>
                        <div class="flex items-center gap-3"><span class="w-8 font-bold text-zinc-500 text-xs text-center">XL</span> <input type="number" name="stok_ukuran[4]" value="{{ old('stok_ukuran.4') }}" class="w-full bg-zinc-950/60 border border-zinc-800/80 p-2 text-sm text-white font-mono rounded-lg outline-none focus:border-zinc-700"></div>
                        <div class="flex items-center gap-3"><span class="w-8 font-bold text-zinc-500 text-xs text-center">XXL</span> <input type="number" name="stok_ukuran[5]" value="{{ old('stok_ukuran.5') }}" class="w-full bg-zinc-950/60 border border-zinc-800/80 p-2 text-sm text-white font-mono rounded-lg outline-none focus:border-zinc-700"></div>
                    </div>
                </div>

                <div class="flex justify-between gap-4 mt-4 border-t border-zinc-800 pt-4">
                    <button type="button" onclick="closeModal('modal-tambah')" class="w-1/2 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-bold py-3 rounded-xl transition-all text-xs uppercase tracking-wider">Batal</button>
                    <button type="submit" class="w-1/2 bg-white hover:bg-zinc-200 text-zinc-950 font-black py-3 rounded-xl transition-all text-xs uppercase tracking-wider shadow-lg">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-edit" class="fixed inset-0 bg-zinc-950/80 backdrop-blur-sm hidden flex items-center justify-center z-50 overflow-y-auto p-4 md:p-10">
        <div class="bg-zinc-900 border border-zinc-800 w-full max-w-lg rounded-2xl p-8 shadow-2xl relative my-auto">
            <h2 class="text-2xl font-black text-white mb-6 uppercase tracking-tight border-b border-zinc-800 pb-3">Edit Data Produk</h2>
            
            <form id="form-edit" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="edit_mode" value="1">
                
                <div class="flex flex-col gap-1.5">
                    <label class="font-bold text-zinc-400 text-xs uppercase tracking-wider">Nama Produk</label>
                    <input type="text" id="edit-nama" name="nama_product" required class="bg-zinc-950/60 text-white border border-zinc-800/80 rounded-xl p-3 text-sm focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all shadow-inner">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="font-bold text-zinc-400 text-xs uppercase tracking-wider">Kategori Produk</label>
                    <select id="edit-category" name="id_category" required class="bg-zinc-950/60 text-white border border-zinc-800/80 rounded-xl p-3 text-sm focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all shadow-inner cursor-pointer appearance-none">
                        <option value="" disabled>-- Pilih Kategori --</option>
                        @isset($categories)
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id_category }}">{{ $cat->nama_category }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                
                <div class="flex flex-col gap-1.5">
                    <label class="font-bold text-zinc-400 text-xs uppercase tracking-wider">Deskripsi</label>
                    <textarea id="edit-deskripsi" name="deskripsi" rows="3" required class="bg-zinc-950/60 text-white border border-zinc-800/80 rounded-xl p-3 text-sm focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all shadow-inner resize-none"></textarea>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="font-bold text-zinc-400 text-xs uppercase tracking-wider">Harga (Rp)</label>
                    <input type="number" id="edit-harga" name="harga" required class="bg-zinc-950/60 text-white border border-zinc-800/80 rounded-xl p-3 text-sm focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all shadow-inner font-mono">
                </div>

                <div class="flex flex-col gap-3 border-t border-b border-zinc-800/80 py-4 my-1">
                    <div class="flex flex-col gap-1">
                        <label class="font-bold text-blue-400 text-xs uppercase tracking-wider">Ganti Foto Utama <span class="text-[10px] font-normal normal-case text-zinc-500">(Kosongkan jika tidak diganti)</span></label>
                        <input type="file" name="foto_utama" accept="image/*" class="text-zinc-400 text-xs file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-zinc-800 file:text-zinc-200 hover:file:bg-zinc-700 cursor-pointer">
                    </div>

                    <div class="flex flex-col gap-1 mt-2">
                        <label class="font-bold text-zinc-400 text-xs uppercase tracking-wider">Ganti Foto Tambahan <span class="text-[10px] font-normal normal-case text-zinc-500">(Kosongkan jika tidak diganti)</span></label>
                        <input type="file" name="foto_tambahan[]" accept="image/*" multiple class="text-zinc-400 text-xs file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-zinc-800 file:text-zinc-200 hover:file:bg-zinc-700 cursor-pointer">
                    </div>
                </div>

                <div class="flex justify-between items-center bg-zinc-950/40 border border-zinc-800/60 p-4 rounded-xl">
                    <label class="text-zinc-300 text-xs font-bold uppercase tracking-wider cursor-pointer select-none" for="edit-multi-size">Aktifkan Multi Size Item</label>
                    <input type="checkbox" id="edit-multi-size" name="is_multi_size" value="1" class="w-5 h-5 cursor-pointer accent-blue-500 rounded border-zinc-800 bg-zinc-950 focus:ring-0 focus:ring-offset-0" onchange="toggleSize('edit')">
                </div>

                <div id="edit-stok-tunggal" class="flex flex-col gap-1.5 block">
                    <label class="font-bold text-zinc-400 text-xs uppercase tracking-wider">Total Stok (All Size)</label>
                    <input type="number" id="edit-stok-tunggal-input" name="stok_tunggal" class="bg-zinc-950/60 text-white border border-zinc-800/80 rounded-xl p-3 text-sm focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all font-mono">
                </div>

                <div id="edit-stok-multi" class="hidden flex-col gap-2 bg-zinc-950/20 p-4 rounded-xl border border-zinc-800/60">
                    <label class="font-bold text-blue-400 text-xs uppercase tracking-wider mb-2">Stok Per Ukuran:</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center gap-3"><span class="w-8 font-bold text-zinc-500 text-xs text-center">S</span> <input type="number" id="edit-stok-ukuran-1" name="stok_ukuran[1]" class="edit-stok-ukuran w-full bg-zinc-950/60 border border-zinc-800/80 p-2 text-sm text-white font-mono rounded-lg outline-none focus:border-zinc-700"></div>
                        <div class="flex items-center gap-3"><span class="w-8 font-bold text-zinc-500 text-xs text-center">M</span> <input type="number" id="edit-stok-ukuran-2" name="stok_ukuran[2]" class="edit-stok-ukuran w-full bg-zinc-950/60 border border-zinc-800/80 p-2 text-sm text-white font-mono rounded-lg outline-none focus:border-zinc-700"></div>
                        <div class="flex items-center gap-3"><span class="w-8 font-bold text-zinc-500 text-xs text-center">L</span> <input type="number" id="edit-stok-ukuran-3" name="stok_ukuran[3]" class="edit-stok-ukuran w-full bg-zinc-950/60 border border-zinc-800/80 p-2 text-sm text-white font-mono rounded-lg outline-none focus:border-zinc-700"></div>
                        <div class="flex items-center gap-3"><span class="w-8 font-bold text-zinc-500 text-xs text-center">XL</span> <input type="number" id="edit-stok-ukuran-4" name="stok_ukuran[4]" class="edit-stok-ukuran w-full bg-zinc-950/60 border border-zinc-800/80 p-2 text-sm text-white font-mono rounded-lg outline-none focus:border-zinc-700"></div>
                        <div class="flex items-center gap-3"><span class="w-8 font-bold text-zinc-500 text-xs text-center">XXL</span> <input type="number" id="edit-stok-ukuran-5" name="stok_ukuran[5]" class="edit-stok-ukuran w-full bg-zinc-950/60 border border-zinc-800/80 p-2 text-sm text-white font-mono rounded-lg outline-none focus:border-zinc-700"></div>
                    </div>
                </div>

                <div class="flex justify-between gap-4 mt-4 border-t border-zinc-800 pt-4">
                    <button type="button" onclick="closeModal('modal-edit')" class="w-1/2 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-bold py-3 rounded-xl transition-all text-xs uppercase tracking-wider">Batal</button>
                    <button type="submit" class="w-1/2 bg-emerald-600 hover:bg-emerald-500 text-white font-black py-3 rounded-xl transition-all text-xs uppercase tracking-wider shadow-lg">Simpan Perubahan</button>
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

        function openEditModal(product, categoryId) {
            document.getElementById('edit-nama').value = product.nama_product;
            document.getElementById('edit-deskripsi').value = product.deskripsi;
            document.getElementById('edit-harga').value = product.harga;
            
            const catSelect = document.getElementById('edit-category');
            if(catSelect && categoryId) {
                catSelect.value = categoryId;
            }
            
            document.getElementById('form-edit').action = '/admin/products/' + product.id_product;

            document.getElementById('edit-stok-tunggal-input').value = '';
            document.querySelectorAll('.edit-stok-ukuran').forEach(input => input.value = '');

            let isMulti = false;
            if (product.variants && product.variants.length > 0) {
                product.variants.forEach(v => {
                    // Cek apakah produk bukan "All Size" (id_size != 6)
                    if (v.id_size !== 6) isMulti = true;
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

        document.addEventListener('DOMContentLoaded', function() {
            @if($errors->any() && !$errors->has('edit_mode'))
                openModal('modal-tambah');
                const modal = document.getElementById('modal-tambah');
                if (modal) modal.scrollTop = 0;
            @endif

            const formTambah = document.querySelector('#modal-tambah form');
            const btnSimpan = formTambah ? formTambah.querySelector('button[type="submit"]') : null;

            if (formTambah && btnSimpan) {
                formTambah.addEventListener('submit', function() {
                    btnSimpan.disabled = true;
                    btnSimpan.innerHTML = '<span class="inline-flex items-center gap-2 justify-center"><svg class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>Menyimpan...</span>';
                });
            }
        });
    </script>
</body>
</html>