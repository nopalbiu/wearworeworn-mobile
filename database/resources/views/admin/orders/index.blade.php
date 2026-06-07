<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <header class="bg-gray-800 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Admin Panel</h1>
            <a href="{{ route('admin.product.index') }}" class="bg-gray-700 px-4 py-2 rounded hover:bg-gray-600">Kembali ke Produk</a>
        </div>
    </header>

    <div class="container mx-auto p-6 mt-4">
        <h2 class="text-2xl font-bold mb-6">Daftar Pesanan</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('admin.orders.index') }}" class="bg-white p-4 rounded-lg shadow mb-6 flex flex-wrap gap-4 items-end">
            <div>
                <label for="sort_id" class="block text-sm font-medium text-gray-700 mb-1">Urutkan ID Transaksi</label>
                <select name="sort_id" id="sort_id" onchange="this.form.submit()" class="border-gray-300 border p-2 rounded focus:outline-none focus:border-gray-500">
                    <option value="desc" {{ request('sort_id') == 'desc' ? 'selected' : '' }}>Terbaru (Terbesar)</option>
                    <option value="asc" {{ request('sort_id') == 'asc' ? 'selected' : '' }}>Terlama (Terkecil)</option>
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Filter Status</label>
                <select name="status" id="status" onchange="this.form.submit()" class="border-gray-300 border p-2 rounded focus:outline-none focus:border-gray-500">
                    <option value="">Semua Status</option>
                    <option value="menunggu pembayaran" {{ request('status') == 'menunggu pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="sudah dibayar" {{ request('status') == 'sudah dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                    <option value="dalam perjalanan" {{ request('status') == 'dalam perjalanan' ? 'selected' : '' }}>Dalam Perjalanan</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.orders.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 flex items-center text-sm font-medium transition-colors">
                    Reset Filter
                </a>
            </div>
        </form>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-gray-700 text-white">
                    <tr>
                        <th class="p-3 border-b whitespace-nowrap">ID Transaksi</th>
                        <th class="p-3 border-b">Nama Akun</th>
                        <th class="p-3 border-b">Nama Customer</th>
                        <th class="p-3 border-b">No Handphone</th>
                        <th class="p-3 border-b">Detail Item</th>
                        <th class="p-3 border-b">Detail Pengiriman</th>
                        <th class="p-3 border-b">Total Harga</th> <th class="p-3 border-b">Status</th>
                        <th class="p-3 border-b">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    
                    @php
                        // Logika memecah string alamat_pengiriman jadi Nama, No HP, dan Alamat murni
                        $rawAlamat = $order->alamat_pengiriman;
                        $parsedNama = '-';
                        $parsedHp = '-';
                        $parsedAlamat = $rawAlamat;

                        if(preg_match('/Nama:\s*(.*?)\s*No\. HP:\s*(.*?)\s*Alamat:\s*(.*)/i', $rawAlamat, $matches)) {
                            $parsedNama = $matches[1];
                            $parsedHp = $matches[2];
                            $parsedAlamat = $matches[3];
                        }
                    @endphp

                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 font-mono">#TRX-{{ str_pad($order->id_order, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="p-3">{{ $order->user->nama ?? 'Akun Terhapus' }}</td>
                        <td class="p-3">{{ $parsedNama }}</td>
                        <td class="p-3">{{ $parsedHp }}</td>
                        <td class="p-3">
                            <ul class="list-disc pl-4">
                            @foreach($order->items as $item)
                                <li>{{ $item->variant->product->nama_product ?? 'Terhapus' }} ({{ $item->variant->size->nama_size ?? '-' }}) - {{ $item->qty }} pcs</li>
                            @endforeach
                            </ul>
                        </td>
                        <td class="p-3">
                            <div>{{ $parsedAlamat }}</div>
                            <div class="text-xs text-gray-500 font-semibold mt-1">Kurir: {{ strtoupper($order->kurir ?? '-') }}</div>
                        </td>
                        <td class="p-3 whitespace-nowrap font-medium text-gray-800">
                            Rp. {{ number_format($order->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="p-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $order->status_pesanan == 'menunggu pembayaran' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->status_pesanan == 'sudah dibayar' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->status_pesanan == 'dalam perjalanan' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $order->status_pesanan == 'selesai' ? 'bg-green-100 text-green-800' : '' }}
                            ">
                                {{ ucwords($order->status_pesanan) }}
                            </span>
                        </td>
                        <td class="p-3">
                            <button onclick="openModal('{{ $order->id_order }}', '{{ $order->status_pesanan }}')" class="bg-gray-600 text-white px-3 py-1.5 rounded hover:bg-gray-800 whitespace-nowrap text-xs">
                                Update
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-4 text-center text-gray-500">Tidak ada data pesanan yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>

    <div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-sm shadow-xl">
            <h3 class="text-lg font-bold mb-4">Update Status Pesanan</h3>
            
            <form id="updateStatusForm" method="POST" action="">
                @csrf
                @method('PUT')
                
                <div class="mb-5">
                    <label for="statusSelectModal" class="block text-sm text-gray-700 font-medium mb-2">Pilih Status Baru</label>
                    <select name="status_pesanan" id="statusSelectModal" class="w-full border-gray-300 border p-2 rounded focus:outline-none focus:border-gray-500 text-sm" required>
                        <option value="menunggu pembayaran">Menunggu Pembayaran</option>
                        <option value="sudah dibayar">Sudah Dibayar</option>
                        <option value="dalam perjalanan">Dalam Perjalanan</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 text-sm">
                        Batal
                    </button>
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900 text-sm">
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