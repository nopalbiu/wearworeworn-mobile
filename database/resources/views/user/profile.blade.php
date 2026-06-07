<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - WearWoreWorn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <header class="bg-white shadow-sm border-b mb-8">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="/" class="text-xl font-bold tracking-wider">WearWoreWorn</a>
            <div class="flex items-center gap-4">
                <a href="{{ route('user.profile') }}" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-full transition">
                    <div class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center text-white font-bold text-sm">
                        {{ substr(auth()->user()->nama ?? 'U', 0, 1) }}
                    </div>
                    <span class="font-medium text-sm">{{ auth()->user()->nama ?? 'User' }}</span>
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-6 py-8 max-w-6xl">
        <div class="flex flex-col md:flex-row gap-8">
            
            <aside class="w-full md:w-1/4 bg-white rounded-xl shadow-sm border border-gray-100 p-4 h-fit sticky top-6">
                <nav class="flex flex-col space-y-1">
                    <button onclick="switchTab('profile')" id="btn-profile" class="sidebar-btn text-left px-4 py-3 rounded-lg bg-gray-800 text-white font-medium transition-colors">
                        My Profile
                    </button>
                    <button onclick="switchTab('orders')" id="btn-orders" class="sidebar-btn text-left px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 font-medium transition-colors">
                        My Orders
                    </button>
                    <hr class="my-2 border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 font-medium transition-colors">
                            Logout
                        </button>
                    </form>
                </nav>
            </aside>

            <div class="w-full md:w-3/4 bg-white rounded-xl shadow-sm border border-gray-100 p-8 min-h-[500px]">
                
                <div id="tab-profile" class="tab-content">
                    <h2 class="text-2xl font-bold mb-6">My Profile</h2>
                    
                    <form action="{{ route('user.profile.update') }}" method="POST" class="max-w-xl space-y-5">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" name="nama" value="{{ auth()->user()->nama ?? '' }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-gray-800 focus:border-gray-800 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-xs text-gray-400 font-normal">(Read-only)</span></label>
                            <input type="email" value="{{ auth()->user()->email ?? '' }}" readonly class="w-full border border-gray-200 bg-gray-50 text-gray-500 rounded-lg px-4 py-2.5 outline-none cursor-not-allowed">
                        </div>
                        
                        <div class="pt-4 flex flex-col sm:flex-row gap-4">
                            <button type="submit" class="bg-gray-800 text-white px-6 py-2.5 rounded-lg hover:bg-gray-900 font-medium">Save Changes</button>
                            <button type="button" onclick="openPasswordModalStep1()" class="border border-gray-300 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-50 font-medium">Change Password</button>
                        </div>
                    </form>
                </div>

                <div id="tab-orders" class="tab-content hidden">
                    <h2 class="text-2xl font-bold mb-6">My Orders</h2>
                    
                    <div class="space-y-4">
                        @forelse($orders as $order)
                        <div class="border border-gray-200 rounded-lg p-5 hover:border-gray-300 transition cursor-pointer" onclick="openOrderModal('{{ $order->id_order }}')">
                            <div class="flex justify-between items-center mb-3">
                                <div class="flex items-center gap-3">
                                    <span class="font-mono text-sm font-semibold">#TRX-{{ str_pad($order->id_order, 4, '0', STR_PAD_LEFT) }}</span>
                                    <span class="text-xs text-gray-500">{{ $order->created_at->format('d M Y') }}</span>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucwords(str_replace('_', ' ', $order->status_pesanan)) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="font-medium">{{ $order->items->count() }} Product(s)</p>
                                </div>
                                <p class="font-bold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500">No orders found. Let's start shopping!</div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </main>

    <div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-md shadow-2xl relative">
            
            <div id="pwd-step-1">
                <h3 class="text-xl font-bold mb-4">Security Verification</h3>
                <p class="text-sm text-gray-600 mb-4">Please enter your current password to proceed.</p>
                <form action="{{ route('user.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" name="current_password" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-gray-800 outline-none">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closePasswordModal()" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Cancel</button>
                        <button type="button" onclick="goToPasswordStep2()" class="px-4 py-2 text-sm bg-gray-800 text-white rounded-lg hover:bg-gray-900">Continue</button>
                    </div>
            </div>

            <div id="pwd-step-2" class="hidden">
                <h3 class="text-xl font-bold mb-4">Create New Password</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" name="password" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-gray-800 outline-none">
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-gray-800 outline-none">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closePasswordModal()" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm bg-gray-800 text-white rounded-lg hover:bg-gray-900">Save Password</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @foreach($orders as $order)
    <div id="orderModal-{{ $order->id_order }}" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl w-full max-w-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="text-lg font-bold">Order Details <span class="text-gray-500 font-mono text-sm ml-2">#TRX-{{ str_pad($order->id_order, 4, '0', STR_PAD_LEFT) }}</span></h3>
                <button onclick="closeOrderModal('{{ $order->id_order }}')" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
            </div>
            
            <div class="p-6 overflow-y-auto">
                <div class="mb-6">
                    <h4 class="font-bold text-gray-800 mb-3 border-b pb-1">Shipping Information</h4>
                    
                    @php
                        $alamatStr = $order->alamat_pengiriman;
                        $namaReceiver = '-';
                        $hpReceiver = '-';
                        $alamatDetail = $alamatStr;

                        if (preg_match('/Nama:\s*(.*?)\s*No\. HP:\s*(.*?)\s*Alamat:\s*(.*)/i', $alamatStr, $matches)) {
                            $namaReceiver = $matches[1];
                            $hpReceiver = $matches[2];
                            $alamatDetail = $matches[3];
                        }
                    @endphp

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <span class="block text-xs text-gray-500 uppercase tracking-wider font-bold mb-1">Receiver Name</span>
                                <span class="block text-sm font-medium text-gray-800">{{ $namaReceiver }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 uppercase tracking-wider font-bold mb-1">Phone Number</span>
                                <span class="block text-sm font-medium text-gray-800">{{ $hpReceiver }}</span>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <span class="block text-xs text-gray-500 uppercase tracking-wider font-bold mb-1">Delivery Address</span>
                            <span class="block text-sm text-gray-700 leading-relaxed">{{ $alamatDetail }}</span>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <span class="block text-xs text-gray-500 uppercase tracking-wider font-bold mb-1">Shipping Courier</span>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-3 py-1 bg-gray-800 text-white text-xs font-bold rounded-md tracking-wider">
                                    {{ strtoupper($order->kurir ?? 'REGULAR') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="font-bold text-gray-800 mb-3 border-b pb-1">Item List</h4>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">
                                {{ $item->variant->product->nama_product ?? 'Deleted Product' }} 
                                ({{ $item->variant->size->nama_size ?? '-' }}) x{{ $item->qty }}
                            </span>
                            @php
                                $hargaSatuan = $item->harga_saat_beli > 0 ? $item->harga_saat_beli : ($item->variant->product->harga ?? 0);
                            @endphp
                            <span class="font-medium">Rp {{ number_format($hargaSatuan * $item->qty, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-gray-800 mb-3 border-b pb-1">Payment Details</h4>
                    <div class="space-y-2 text-sm">
                        
                        @php
                            $subtotalProduk = $order->items->sum(function($item) {
                                $harga = $item->harga_saat_beli > 0 ? $item->harga_saat_beli : ($item->variant->product->harga ?? 0);
                                return $harga * $item->qty;
                            });

                            $namaKurir = strtoupper($order->kurir ?? '');
                            $shippingFee = 0;
                            $shippingInsurance = 0;

                            if (str_contains($namaKurir, 'JNE')) {
                                $shippingFee = 15000;
                                $shippingInsurance = 3000;
                            } elseif (str_contains($namaKurir, 'J&T') || str_contains($namaKurir, 'JNT')) {
                                $shippingFee = 13000;
                                $shippingInsurance = 2500;
                            } elseif (str_contains($namaKurir, 'SICEPAT') || str_contains($namaKurir, 'SI CEPAT')) {
                                $shippingFee = 12000;
                                $shippingInsurance = 2000;
                            } else {
                                $shippingFee = max(0, $order->total_harga - $subtotalProduk);
                                $shippingInsurance = 0;
                            }
                        @endphp

                        <div class="flex justify-between text-gray-600 mt-2">
                            <span>Product Subtotal</span>
                            <span>Rp {{ number_format($subtotalProduk, 0, ',', '.') }}</span>
                        </div>

                        @if($shippingFee > 0)
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping Fee</span>
                            <span>Rp {{ number_format($shippingFee, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        @if($shippingInsurance > 0)
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping Insurance</span>
                            <span>Rp {{ number_format($shippingInsurance, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between font-bold text-lg mt-3 pt-3 border-t">
                            <span>Grand Total</span>
                            <span class="text-gray-800">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endforeach

    <script>
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
            });
            
            document.getElementById(`tab-${tabName}`).classList.remove('hidden');

            document.querySelectorAll('.sidebar-btn').forEach(btn => {
                btn.classList.remove('bg-gray-800', 'text-white');
                btn.classList.add('text-gray-600', 'hover:bg-gray-100');
            });

            const activeBtn = document.getElementById(`btn-${tabName}`);
            activeBtn.classList.remove('text-gray-600', 'hover:bg-gray-100');
            activeBtn.classList.add('bg-gray-800', 'text-white');
        }

        const pwdModal = document.getElementById('passwordModal');
        const pwdStep1 = document.getElementById('pwd-step-1');
        const pwdStep2 = document.getElementById('pwd-step-2');

        function openPasswordModalStep1() {
            pwdModal.classList.remove('hidden');
            pwdModal.classList.add('flex');
            pwdStep1.classList.remove('hidden');
            pwdStep2.classList.add('hidden');
        }

        function goToPasswordStep2() {
            pwdStep1.classList.add('hidden');
            pwdStep2.classList.remove('hidden');
        }

        function closePasswordModal() {
            pwdModal.classList.add('hidden');
            pwdModal.classList.remove('flex');
            pwdStep1.classList.remove('hidden');
            pwdStep2.classList.add('hidden');
        }

        function openOrderModal(id) {
            const modal = document.getElementById('orderModal-' + id);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeOrderModal(id) {
            const modal = document.getElementById('orderModal-' + id);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</body>
</html>