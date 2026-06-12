<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - WearWoreWorn</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen font-sans selection:bg-blue-900/50 selection:text-blue-100 flex flex-col">

    <nav class="bg-zinc-950/80 backdrop-blur-md border-b border-zinc-800/60 p-4 sticky top-0 z-50 shadow-sm relative mb-8">
        <div class="container mx-auto flex items-center justify-between md:grid md:grid-cols-3">
            
            <div class="flex justify-start">
                <a href="/" class="flex items-center transition-transform duration-300 hover:scale-105">
                    <img src="{{ asset('images/logo-www.png') }}" alt="WearWoreWorn Logo" class="h-8 w-auto object-contain">
                </a>
            </div>
            
            <div class="hidden md:flex justify-center space-x-12 font-medium text-sm tracking-widest text-zinc-400 w-full">
                <a href="/" class="hover:text-blue-400 transition-colors">CATALOG</a>
                <a href="{{ route('about') }}" class="hover:text-blue-400 transition-colors">ABOUT US</a>
            </div>

            <div class="flex justify-end items-center space-x-6">
                <a href="{{ route('cart.index') }}" class="text-zinc-400 hover:text-blue-400 transition-colors relative group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </a>

                <a href="{{ route('user.profile') }}" class="flex items-center gap-2 bg-zinc-900 hover:bg-zinc-800 border border-zinc-700 hover:border-blue-500/50 px-4 py-2 rounded-full transition-all text-white drop-shadow-[0_0_8px_rgba(96,165,250,0.3)]">
                    <div class="w-6 h-6 bg-blue-600/20 border border-blue-500 text-blue-400 rounded-full flex items-center justify-center font-bold text-xs">
                        {{ substr(auth()->user()->nama ?? 'U', 0, 1) }}
                    </div>
                    <span class="font-semibold text-sm tracking-wide truncate max-w-[100px]">{{ auth()->user()->nama ?? 'User' }}</span>
                </a>
            </div>
            
        </div>
    </nav>

    <main class="flex-grow container mx-auto p-4 lg:p-8 max-w-6xl">
        <div class="flex flex-col lg:flex-row gap-10">
            
            <aside class="w-full lg:w-1/4 bg-zinc-900/40 rounded-xl border border-zinc-800/80 backdrop-blur-sm p-5 h-fit sticky top-24 shadow-sm">
                <nav class="flex flex-col gap-2">
                    <button onclick="switchTab('profile')" id="btn-profile" class="sidebar-btn text-left px-4 py-3 rounded-lg bg-zinc-800 text-zinc-100 border border-zinc-700 font-medium transition-all duration-300">
                        My Profile
                    </button>
                    <button onclick="switchTab('orders')" id="btn-orders" class="sidebar-btn text-left px-4 py-3 rounded-lg text-zinc-400 hover:bg-zinc-900/60 hover:text-zinc-200 font-medium transition-all duration-300">
                        My Orders
                    </button>
                    <hr class="my-2 border-zinc-800/60">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 rounded-lg text-red-400 hover:bg-red-950/30 border border-transparent hover:border-red-900/30 font-medium transition-colors">
                            Logout
                        </button>
                    </form>
                </nav>
            </aside>

            <div class="w-full lg:w-3/4 bg-zinc-900/40 rounded-xl border border-zinc-800/80 backdrop-blur-sm p-8 min-h-[500px] shadow-sm">
                
                <div id="tab-profile" class="tab-content">
                    <h2 class="text-2xl font-bold mb-6 text-white tracking-wide uppercase border-b border-zinc-800/60 pb-4">My Profile</h2>
                    
                    <form action="{{ route('user.profile.update') }}" method="POST" class="max-w-xl space-y-5">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold tracking-widest text-zinc-500 mb-2 uppercase">Full Name</label>
                            <input type="text" name="nama" value="{{ auth()->user()->nama ?? '' }}" class="w-full bg-zinc-950/50 text-white placeholder-zinc-600 border border-zinc-800 rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-all shadow-inner">
                        </div>
                        <div>
                            <label class="block text-xs font-bold tracking-widest text-zinc-500 mb-2 uppercase">Email Address <span class="text-xs text-zinc-600 font-normal normal-case">(Read-only)</span></label>
                            <input type="email" value="{{ auth()->user()->email ?? '' }}" readonly class="w-full bg-zinc-950 border border-zinc-900 text-zinc-500 rounded-lg px-4 py-2.5 outline-none cursor-not-allowed shadow-inner">
                        </div>
                        
                        <div class="pt-4 flex flex-col sm:flex-row gap-4">
                            <button type="submit" class="bg-white hover:bg-zinc-200 text-zinc-950 text-sm font-bold px-6 py-2.5 rounded-lg transition-all shadow-[0_0_15px_rgba(255,255,255,0.1)] hover:shadow-[0_0_20px_rgba(255,255,255,0.2)]">Save Changes</button>
                            <button type="button" onclick="openPasswordModalStep1()" class="bg-zinc-900 hover:bg-zinc-800 border border-zinc-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg transition-all">Change Password</button>
                        </div>
                    </form>
                </div>

                <div id="tab-orders" class="tab-content hidden">
                    <h2 class="text-2xl font-bold mb-6 text-white tracking-wide uppercase border-b border-zinc-800/60 pb-4">My Orders</h2>
                    
                    <div class="space-y-4">
                        @forelse($orders as $order)
                        @if($order->status_pesanan === 'menunggu_pembayaran')
                        {{-- Order belum dibayar: klik → ke halaman instruksi pembayaran --}}
                        <a href="{{ route('checkout.success', $order->id_order) }}"
                           class="block border border-blue-900/50 bg-blue-950/10 rounded-xl p-5 hover:border-blue-500/50 hover:shadow-[0_0_20px_rgba(59,130,246,0.1)] transition-all duration-300 cursor-pointer flex flex-col gap-3 group">
                            <div class="flex justify-between items-center mb-1">
                                <div class="flex items-center gap-3">
                                    <span class="font-mono text-sm font-semibold text-blue-400">#TRX-{{ str_pad($order->id_order, 4, '0', STR_PAD_LEFT) }}</span>
                                    <span class="text-xs text-zinc-500">{{ $order->created_at->format('d M Y') }}</span>
                                </div>
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-900/30 text-blue-300 border border-blue-700/50 flex items-center gap-1.5">
                                    <span class="relative flex h-1.5 w-1.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-blue-500"></span>
                                    </span>
                                    Menunggu Pembayaran
                                </span>
                            </div>
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="font-medium text-zinc-300 text-sm">{{ $order->items->count() }} Product(s)</p>
                                    <p class="text-xs text-blue-400/70 mt-1 group-hover:text-blue-400 transition-colors">
                                        ↗ Klik untuk lihat instruksi pembayaran
                                    </p>
                                </div>
                                <p class="font-bold text-white tracking-wide">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                            </div>
                        </a>
                        @else
                        {{-- Order lain: klik → buka modal detail --}}
                        <div class="border border-zinc-800/80 bg-zinc-950/30 rounded-xl p-5 hover:border-blue-900/40 hover:shadow-[0_0_20px_rgba(30,58,138,0.1)] transition-all duration-300 cursor-pointer flex flex-col gap-3" onclick="openOrderModal('{{ $order->id_order }}')">
                            <div class="flex justify-between items-center mb-1">
                                <div class="flex items-center gap-3">
                                    <span class="font-mono text-sm font-semibold text-blue-400">#TRX-{{ str_pad($order->id_order, 4, '0', STR_PAD_LEFT) }}</span>
                                    <span class="text-xs text-zinc-500">{{ $order->created_at->format('d M Y') }}</span>
                                </div>
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-zinc-800 text-zinc-300 border border-zinc-700">
                                    {{ ucwords(str_replace('_', ' ', $order->status_pesanan)) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="font-medium text-zinc-300 text-sm">{{ $order->items->count() }} Product(s)</p>
                                </div>
                                <p class="font-bold text-white tracking-wide">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endif
                        @empty
                        <div class="text-center py-24 text-zinc-500 border border-zinc-800/50 border-dashed rounded-2xl bg-zinc-900/20">
                            <div class="bg-zinc-900 p-4 rounded-full mb-4 shadow-inner inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <p class="text-zinc-400 text-sm font-medium">No orders found. Let's start shopping!</p>
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </main>

    <div id="passwordModal" class="fixed inset-0 bg-zinc-950/80 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-zinc-900 border border-zinc-800 text-zinc-100 p-6 rounded-xl w-full max-w-md shadow-2xl relative">
            
            <div id="pwd-step-1">
                <h3 class="text-xl font-bold mb-2 text-white tracking-wide uppercase">Security Verification</h3>
                <p class="text-sm text-zinc-400 mb-5">Please enter your current password to proceed.</p>
                <form action="{{ route('user.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-5">
                        <label class="block text-xs font-bold tracking-widest text-zinc-500 mb-2 uppercase">Current Password</label>
                        <input type="password" name="current_password" required class="w-full bg-zinc-950/50 text-white border border-zinc-800 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-all">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closePasswordModal()" class="px-4 py-2 text-sm text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800 rounded-lg transition-colors">Cancel</button>
                        <button type="button" onclick="goToPasswordStep2()" class="px-4 py-2 text-sm bg-white hover:bg-zinc-200 text-zinc-950 font-bold rounded-lg transition-all">Continue</button>
                    </div>
            </div>

            <div id="pwd-step-2" class="hidden">
                <h3 class="text-xl font-bold mb-2 text-white tracking-wide uppercase">Create New Password</h3>
                <p class="text-sm text-zinc-400 mb-5">Ensure your account is protected with a strong password.</p>
                    <div class="mb-4">
                        <label class="block text-xs font-bold tracking-widest text-zinc-500 mb-2 uppercase">New Password</label>
                        <input type="password" name="password" required class="w-full bg-zinc-950/50 text-white border border-zinc-800 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-all">
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-bold tracking-widest text-zinc-500 mb-2 uppercase">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required class="w-full bg-zinc-950/50 text-white border border-zinc-800 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-all">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closePasswordModal()" class="px-4 py-2 text-sm text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800 rounded-lg transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm bg-white hover:bg-zinc-200 text-zinc-950 font-bold rounded-lg transition-all">Save Password</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @foreach($orders as $order)
    <div id="orderModal-{{ $order->id_order }}" class="fixed inset-0 bg-zinc-950/80 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl w-full max-w-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="p-5 border-b border-zinc-800/60 flex justify-between items-center bg-zinc-950/50">
                <h3 class="text-lg font-bold text-white tracking-wide uppercase">Order Details <span class="text-blue-400 font-mono text-sm ml-2">#TRX-{{ str_pad($order->id_order, 4, '0', STR_PAD_LEFT) }}</span></h3>
                <button onclick="closeOrderModal('{{ $order->id_order }}')" class="text-zinc-500 hover:text-zinc-300 text-xl font-bold transition-colors">&times;</button>
            </div>
            
            <div class="p-6 overflow-y-auto space-y-6">
                <div>
                    <h4 class="font-bold text-xs tracking-widest text-zinc-500 mb-3 border-b border-zinc-800/60 pb-1 uppercase">Shipping Information</h4>
                    
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

                    <div class="bg-zinc-950/50 rounded-lg p-4 border border-zinc-800/80 shadow-inner">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <span class="block text-[10px] text-zinc-500 uppercase tracking-wider font-bold mb-1">Receiver Name</span>
                                <span class="block text-sm font-medium text-zinc-200">{{ $namaReceiver }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] text-zinc-500 uppercase tracking-wider font-bold mb-1">Phone Number</span>
                                <span class="block text-sm font-medium text-zinc-200">{{ $hpReceiver }}</span>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <span class="block text-[10px] text-zinc-500 uppercase tracking-wider font-bold mb-1">Delivery Address</span>
                            <span class="block text-sm text-zinc-400 leading-relaxed">{{ $alamatDetail }}</span>
                        </div>

                        <div class="pt-3 border-t border-zinc-800/60">
                            <span class="block text-[10px] text-zinc-500 uppercase tracking-wider font-bold mb-1">Shipping Courier</span>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-3 py-1 bg-blue-950/40 text-blue-300 border border-blue-800/50 text-xs font-bold rounded-md tracking-wider">
                                    {{ strtoupper($order->kurir ?? 'REGULAR') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-xs tracking-widest text-zinc-500 mb-3 border-b border-zinc-800/60 pb-1 uppercase">Item List</h4>
                    <div class="space-y-3 bg-zinc-950/30 p-4 rounded-lg border border-zinc-800/40">
                        @foreach($order->items as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-400">
                                {{ $item->variant->product->nama_product ?? 'Deleted Product' }} 
                                <span class="text-zinc-600">({{ $item->variant->size->nama_size ?? '-' }})</span> <span class="text-blue-400/80 font-mono">x{{ $item->qty }}</span>
                            </span>
                            @php
                                $hargaSatuan = $item->harga_saat_beli > 0 ? $item->harga_saat_beli : ($item->variant->product->harga ?? 0);
                            @endphp
                            <span class="font-medium text-zinc-200">Rp {{ number_format($hargaSatuan * $item->qty, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-xs tracking-widest text-zinc-500 mb-3 border-b border-zinc-800/60 pb-1 uppercase">Payment Details</h4>
                    <div class="space-y-2 text-sm px-1">
                        
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

                        <div class="flex justify-between text-zinc-400 mt-2">
                            <span>Product Subtotal</span>
                            <span class="text-zinc-200">Rp {{ number_format($subtotalProduk, 0, ',', '.') }}</span>
                        </div>

                        @if($shippingFee > 0)
                        <div class="flex justify-between text-zinc-400">
                            <span>Shipping Fee</span>
                            <span class="text-zinc-200">Rp {{ number_format($shippingFee, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        @if($shippingInsurance > 0)
                        <div class="flex justify-between text-zinc-400">
                            <span>Shipping Insurance</span>
                            <span class="text-zinc-200">Rp {{ number_format($shippingInsurance, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between font-bold text-lg mt-4 pt-3 border-t border-zinc-800/60">
                            <span class="text-zinc-400 uppercase tracking-wider text-sm flex items-center">Grand Total</span>
                            <span class="text-blue-400 font-extrabold tracking-wide">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endforeach

    <footer class="mt-16 border-t border-zinc-800/60 bg-zinc-950 pt-12 pb-8">
        <div class="container mx-auto px-4 text-center flex flex-col items-center">
            <div class="mb-6 opacity-40 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-500">
                <img src="{{ asset('images/logo-www.png') }}" alt="WearWoreWorn Logo" class="h-6 w-auto object-contain">
            </div>
            <p class="text-zinc-600 text-sm mb-6 max-w-md">
                Elevating street essentials. Crafted with precision, designed for the culture.
            </p>
            <p class="text-zinc-700 text-xs tracking-wider uppercase">&copy; 2026 WearWoreWorn. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
            });
            
            document.getElementById(`tab-${tabName}`).classList.remove('hidden');

            document.querySelectorAll('.sidebar-btn').forEach(btn => {
                btn.classList.remove('bg-zinc-800', 'text-zinc-100', 'border-zinc-700');
                btn.classList.add('text-zinc-400', 'hover:bg-zinc-900/60', 'hover:text-zinc-200');
            });

            const activeBtn = document.getElementById(`btn-${tabName}`);
            activeBtn.classList.remove('text-zinc-400', 'hover:bg-zinc-900/60', 'hover:text-zinc-200');
            activeBtn.classList.add('bg-zinc-800', 'text-zinc-100', 'border-zinc-700');
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