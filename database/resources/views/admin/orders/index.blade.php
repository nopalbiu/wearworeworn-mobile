<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan - Admin</title>
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
            <a href="{{ route('admin.product.index') }}" class="text-zinc-400 hover:text-white hover:bg-zinc-800/60 font-bold px-6 py-2 rounded-lg text-xs tracking-wider uppercase transition-all">PRODUCT</a>    
            <a href="{{ route('admin.orders.index') }}" class="bg-white text-zinc-950 font-black px-6 py-2 rounded-lg text-xs tracking-wider uppercase transition-all shadow-[0_0_15px_rgba(255,255,255,0.1)]">ORDERS</a>
        </div>
        
        <div class="flex justify-end">
            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                @csrf
                <button type="submit" class="bg-transparent border border-zinc-800 hover:border-zinc-700 text-zinc-400 hover:text-white font-bold px-4 py-2 text-xs tracking-wider rounded-lg transition-all uppercase">LOG OUT</button>
            </form>
        </div>

    </nav>

    <div class="container mx-auto p-6 mt-4">
        <h2 class="text-2xl font-black text-white uppercase tracking-tight mb-6">Daftar Pesanan</h2>

        @if(session('success'))
            <div class="bg-emerald-950/30 border border-emerald-900 text-emerald-400 p-4 rounded mb-6 text-sm font-medium shadow-inner flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('admin.orders.index') }}" class="bg-zinc-900/30 backdrop-blur-md border border-zinc-800/80 p-4 rounded-lg shadow mb-6 flex flex-wrap gap-4 items-end shadow-2xl">
            <div>
                <label for="sort_id" class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-1">Urutkan ID Transaksi</label>
                <select name="sort_id" id="sort_id" onchange="this.form.submit()" class="bg-zinc-950/60 text-white border border-zinc-800/80 p-2 rounded focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all cursor-pointer text-sm">
                    <option value="desc" {{ request('sort_id') == 'desc' ? 'selected' : '' }}>Terbaru (Terbesar)</option>
                    <option value="asc" {{ request('sort_id') == 'asc' ? 'selected' : '' }}>Terlama (Terkecil)</option>
                </select>
            </div>

            <div>
                <label for="status" class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-1">Filter Status</label>
                <select name="status" id="status" onchange="this.form.submit()" class="bg-zinc-950/60 text-white border border-zinc-800/80 p-2 rounded focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all cursor-pointer text-sm">
                    <option value="">Semua Status</option>
                    <option value="menunggu pembayaran" {{ request('status') == 'menunggu pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="sudah dibayar" {{ request('status') == 'sudah dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                    <option value="dalam perjalanan" {{ request('status') == 'dalam perjalanan' ? 'selected' : '' }}>Dalam Perjalanan</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.orders.index') }}" class="bg-zinc-800 hover:bg-zinc-700 border border-zinc-700/60 text-zinc-300 px-4 py-2 rounded flex items-center text-sm font-bold tracking-wider uppercase transition-colors">
                    Reset Filter
                </a>
            </div>
        </form>

        <div class="bg-zinc-900/20 rounded-lg shadow border border-zinc-800/60 overflow-x-auto shadow-2xl">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-zinc-900/80 border-b border-zinc-800 text-zinc-400 text-xs font-bold tracking-widest uppercase">
                    <tr>
                        <th class="p-3 border-b border-zinc-800 whitespace-nowrap">ID Transaksi</th>
                        <th class="p-3 border-b border-zinc-800">Nama Akun</th>
                        <th class="p-3 border-b border-zinc-800">Nama Customer</th>
                        <th class="p-3 border-b border-zinc-800">No Handphone</th>
                        <th class="p-3 border-b border-zinc-800">Detail Item</th>
                        <th class="p-3 border-b border-zinc-800">Detail Pengiriman</th>
                        <th class="p-3 border-b border-zinc-800">Total Harga</th> 
                        <th class="p-3 border-b border-zinc-800">Status</th>
                        <th class="p-3 border-b border-zinc-800">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/40 text-zinc-300">
                    @forelse($orders as $order)
                    
                    @php
                        $rawAlamat = $order->alamat_pengiriman;
                        $parsedNama = '-';
                        $parsedHp = '-';
                        $parsedAlamat = $rawAlamat;

                        if(preg_match('/Nama:\s*(.*?)\s*No\. HP:\s*(.*?)\s*Alamat:\s*(.*)/i', $rawAlamat, $matches)) {
                            $parsedNama = $matches[1];
                            $parsedHp = $matches[2];
                            $parsedAlamat = $matches[3];
                        }

                        $statusBersih = strtolower(str_replace('_', ' ', $order->status_pesanan));
                    @endphp

                    <tr class="border-b border-zinc-800/30 bg-zinc-900/10 hover:bg-zinc-900/50 transition-all">
                        <td class="p-3 font-mono text-xs text-zinc-500">#TRX-{{ str_pad($order->id_order, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="p-3 font-semibold text-zinc-200">{{ $order->user->nama ?? 'Akun Terhapus' }}</td>
                        <td class="p-3 text-zinc-300">{{ $parsedNama }}</td>
                        <td class="p-3 font-mono text-xs text-zinc-400">{{ $parsedHp }}</td>
                        <td class="p-3 text-xs">
                            <ul class="list-disc pl-4 space-y-1 text-zinc-400">
                            @foreach($order->items as $item)
                                <li><span class="text-white font-medium">{{ $item->variant->product->nama_product ?? 'Terhapus' }}</span> ({{ $item->variant->size->nama_size ?? '-' }}) - <span class="font-mono text-zinc-300">{{ $item->qty }} pcs</span></li>
                            @endforeach
                            </ul>
                        </td>
                        <td class="p-3 text-xs">
                            <div class="text-zinc-300 leading-relaxed">{{ $parsedAlamat }}</div>
                            <div class="text-[10px] font-bold tracking-wider text-blue-400 uppercase mt-2 bg-blue-950/40 border border-blue-900/40 px-2 py-0.5 rounded w-max">Kurir: {{ strtoupper($order->kurir ?? '-') }}</div>
                        </td>
                        <td class="p-3 whitespace-nowrap font-mono font-bold text-zinc-100">
                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="p-3">
                            <span class="px-2.5 py-1 text-xs font-bold uppercase tracking-wider rounded-md border inline-block text-center w-full whitespace-nowrap
                                {{ $statusBersih == 'menunggu pembayaran' ? 'bg-amber-950/40 text-amber-400 border-amber-900/50' : '' }}
                                {{ $statusBersih == 'sudah dibayar' ? 'bg-blue-950/40 text-blue-400 border-blue-900/50' : '' }}
                                {{ $statusBersih == 'dalam perjalanan' ? 'bg-purple-950/40 text-purple-400 border-purple-900/50' : '' }}
                                {{ $statusBersih == 'selesai' ? 'bg-emerald-950/40 text-emerald-400 border-emerald-900/50' : '' }}
                            ">
                                {{ $statusBersih }}
                            </span>
                        </td>
                        <td class="p-3">
                            <button onclick="openModal('{{ $order->id_order }}', '{{ $statusBersih }}')" class="bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 text-zinc-200 font-bold px-3 py-1.5 rounded whitespace-nowrap text-xs tracking-wider uppercase transition-all">
                                Update
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-12 text-center font-medium text-zinc-500 tracking-wide bg-zinc-900/5">Tidak ada data pesanan yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 text-zinc-400">
            {{ $orders->links() }}
        </div>
    </div>

    <div id="statusModal" class="fixed inset-0 bg-zinc-950/80 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="bg-zinc-900 border border-zinc-800 w-full max-w-sm rounded-2xl p-6 shadow-2xl">
            <h3 class="text-lg font-black text-white mb-4 uppercase tracking-tight border-b border-zinc-800 pb-2">Update Status Pesanan</h3>
            
            <form id="updateStatusForm" method="POST" action="">
                @csrf
                @method('PUT')
                
                <div class="mb-5">
                    <label for="statusSelectModal" class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Pilih Status Baru</label>
                    <select name="status_pesanan" id="statusSelectModal" class="w-full bg-zinc-950/60 text-white border border-zinc-800/80 p-2.5 rounded focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 text-sm cursor-pointer outline-none transition-all" required>
                        <option value="menunggu pembayaran">Menunggu Pembayaran</option>
                        <option value="sudah dibayar">Sudah Dibayar</option>
                        <option value="dalam perjalanan">Dalam Perjalanan</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                
                <div class="flex justify-end gap-2 border-t border-zinc-800/60 pt-4">
                    <button type="button" onclick="closeModal()" class="bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-bold px-4 py-2 rounded text-sm uppercase tracking-wider transition-all">
                        Batal
                    </button>
                    <button type="submit" class="bg-white hover:bg-zinc-200 text-zinc-950 font-black px-4 py-2 rounded text-sm uppercase tracking-wider transition-all shadow-lg">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(orderId, currentStatus) {
            document.getElementById('statusModal').classList.remove('hidden');
            const form = document.getElementById('updateStatusForm');
            form.action = `/admin/orders/${orderId}/status`;
            document.getElementById('statusSelectModal').value = currentStatus;
        }

        function closeModal() {
            document.getElementById('statusModal').classList.add('hidden');
        }
    </script>
</body>
</html>