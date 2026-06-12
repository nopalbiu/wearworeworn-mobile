<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - WearWoreWorn</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen font-sans selection:bg-blue-900/50 selection:text-blue-100 flex flex-col relative">

    <div class="fixed top-0 right-0 -mr-32 -mt-32 w-96 h-96 bg-blue-600/5 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="fixed bottom-0 left-0 -ml-32 -mb-32 w-96 h-96 bg-blue-600/5 rounded-full blur-[120px] pointer-events-none"></div>

    <nav class="bg-zinc-950/80 backdrop-blur-md border-b border-zinc-800/60 p-4 sticky top-0 z-50 shadow-sm relative">
        <div class="container mx-auto flex justify-between items-center relative">
            
            <a href="/" class="flex items-center transition-transform duration-300 hover:scale-105 relative z-10">
                <img src="{{ asset('images/logo-www.png') }}" alt="WearWoreWorn Logo" class="h-8 w-auto object-contain">
            </a>
            
            <div class="hidden md:flex space-x-12 font-medium text-sm tracking-widest text-zinc-400 absolute left-1/2 transform -translate-x-1/2 w-max">
                <a href="/" class="hover:text-blue-400 transition-colors">CATALOG</a>
                <a href="{{ route('about') }}" class="hover:text-blue-400 transition-colors">ABOUT US</a>
            </div>

            <div class="flex items-center space-x-6 relative z-10">
                <a href="{{ route('cart.index') }}" class="text-zinc-100 hover:text-blue-400 transition-colors relative group drop-shadow-[0_0_8px_rgba(96,165,250,0.3)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </a>

                @auth
                    <a href="{{ route('user.profile') }}" class="bg-zinc-900 hover:bg-zinc-800 border border-zinc-700 hover:border-blue-500/50 text-white text-sm font-semibold px-5 py-2 rounded-full transition-all">Account</a>
                @else
                    <a href="{{ route('login') }}" class="text-zinc-400 hover:text-blue-400 text-sm font-semibold transition-colors">Log In</a>
                    <a href="{{ route('register') }}" class="bg-white hover:bg-zinc-200 text-zinc-950 text-sm font-bold px-5 py-2 rounded-full transition-all shadow-[0_0_15px_rgba(255,255,255,0.1)] hover:shadow-[0_0_20px_rgba(255,255,255,0.2)]">Sign Up</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto p-4 md:p-8 mt-4 relative z-10">
        <div class="max-w-6xl mx-auto py-4 px-2 sm:px-4">
            
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight uppercase">Checkout</h1>
                <p class="text-zinc-500 mt-2 text-sm">Please review your shipping details and complete your payment.</p>
            </div>

            @if($errors->any())
                <div class="bg-red-900/20 border border-red-500/50 text-red-400 px-6 py-4 rounded-xl mb-6 shadow-sm backdrop-blur-sm">
                    <ul class="list-disc list-inside space-y-1 text-sm font-medium flex flex-col gap-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($hasOutOfStock)
                <div class="bg-yellow-900/20 border border-yellow-500/50 text-yellow-400 px-6 py-4 rounded-xl mb-8 shadow-sm flex items-start backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="font-medium text-sm leading-relaxed">Beberapa item di keranjang memiliki stok yang tidak mencukupi. Silakan kembali ke keranjang untuk menyesuaikan pesanan.</p>
                </div>
            @endif

            <form action="{{ route('checkout.process') }}" method="POST" class="flex flex-col lg:flex-row gap-8 lg:gap-10">
                @csrf
                
                @foreach($selectedItems as $itemId)
                    <input type="hidden" name="items[]" value="{{ $itemId }}">
                @endforeach
                
                <div class="w-full lg:w-3/5 flex flex-col gap-8">
                    
                    <div class="bg-zinc-900/40 rounded-2xl p-6 sm:p-8 border border-zinc-800/80 shadow-xl backdrop-blur-md">
                        <div class="flex items-center gap-3 border-b border-zinc-800/60 pb-4 mb-6">
                            <div class="bg-blue-500/10 p-2 rounded-lg text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-white uppercase tracking-wide">Shipping Address</h3>
                        </div>
                        
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold tracking-widest text-zinc-500 uppercase mb-2">Name</label>
                                <input type="text" name="nama_penerima" value="{{ old('nama_penerima', $user->nama) }}" required 
                                       class="w-full px-4 py-3 bg-zinc-950/50 border border-zinc-800 rounded-xl focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 text-white placeholder-zinc-600 transition-all text-sm">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold tracking-widest text-zinc-500 uppercase mb-2">Phone Number</label>
                                <input type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="e.g., 081234567890"
                                       pattern="[0-9]{11,20}" title="Nomor handphone harus berupa angka dengan minimal 11 digit" 
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                       class="w-full px-4 py-3 bg-zinc-950/50 border border-zinc-800 rounded-xl focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 text-white placeholder-zinc-600 transition-all text-sm">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold tracking-widest text-zinc-500 uppercase mb-2">Full Address</label>
                                <textarea name="alamat" rows="4" required placeholder="Jalan, RT/RW, Kecamatan, Kota, Provinsi, Kode Pos..."
                                          class="w-full px-4 py-3 bg-zinc-950/50 border border-zinc-800 rounded-xl focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 text-white placeholder-zinc-600 transition-all text-sm resize-none">{{ old('alamat') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-zinc-900/40 rounded-2xl p-6 sm:p-8 border border-zinc-800/80 shadow-xl backdrop-blur-md">
                        <div class="flex items-center gap-3 border-b border-zinc-800/60 pb-4 mb-6">
                            <div class="bg-blue-500/10 p-2 rounded-lg text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-white uppercase tracking-wide">Courier Provider</h3>
                        </div>
                        
                        <div class="relative">
                            <select name="kurir" id="kurir-select" required class="w-full px-4 py-3.5 bg-zinc-950/50 border border-zinc-800 rounded-xl focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 text-white transition-all text-sm font-medium appearance-none cursor-pointer">
                                <option value="" data-ongkir="0" data-asuransi="0" disabled {{ old('kurir') ? '' : 'selected' }}>Select Courier Service</option>
                                @foreach($courierOptions as $name => $data)
                                    <option value="{{ $name }}" data-ongkir="{{ $data['ongkir'] }}" data-asuransi="{{ $data['asuransi'] }}" {{ old('kurir') === $name ? 'selected' : '' }} class="bg-zinc-900">
                                        {{ $name }} - Rp {{ number_format($data['ongkir'], 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-zinc-500">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-2/5 flex flex-col gap-8">
                    
                    <div class="bg-zinc-900/40 rounded-2xl p-6 sm:p-8 border border-zinc-800/80 shadow-xl backdrop-blur-md">
                        <div class="flex items-center gap-3 border-b border-zinc-800/60 pb-4 mb-6">
                            <div class="bg-blue-500/10 p-2 rounded-lg text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-white uppercase tracking-wide">Order Summary</h3>
                        </div>
                        
                        <div class="space-y-5 mb-6 max-h-[300px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-zinc-700 scrollbar-track-transparent">
                            @foreach($cart->items as $item)
                            <div class="flex justify-between items-start text-sm group">
                                <div class="text-zinc-300 w-2/3 pr-3">
                                    <p class="font-medium truncate group-hover:text-blue-400 transition-colors" title="{{ $item->variant->product->nama_product }}">
                                        {{ $item->variant->product->nama_product }}
                                    </p>
                                    <p class="text-zinc-500 text-xs uppercase mt-1 tracking-wider">Size: {{ $item->variant->size->nama_size }} &times; {{ $item->qty }}</p>
                                </div>
                                <div class="text-zinc-100 font-medium whitespace-nowrap">
                                    Rp {{ number_format($item->qty * $item->variant->product->harga, 0, ',', '.') }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="border-t border-zinc-800/60 pt-5 space-y-3 text-sm">
                            <div class="flex justify-between items-center text-zinc-400">
                                <span>Subtotal</span>
                                <span id="total-item-price" data-value="{{ $totalItemPrice }}" class="text-zinc-200 font-medium">Rp {{ number_format($totalItemPrice, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-zinc-400">
                                <span>Shipping Fee</span>
                                <span id="shipping-fee" class="text-zinc-200 font-medium">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center text-zinc-400">
                                <span>Insurance</span>
                                <span id="shipping-insurance" class="text-zinc-200 font-medium">Rp 0</span>
                            </div>
                            
                            <div class="flex justify-between items-center pt-5 mt-3 border-t border-zinc-800/60">
                                <span class="text-zinc-300 font-bold tracking-widest uppercase text-sm">Total</span>
                                <span id="total-cost" class="text-blue-400 font-extrabold text-xl">Rp {{ number_format($totalItemPrice, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-zinc-900/40 rounded-2xl p-6 sm:p-8 border border-zinc-800/80 shadow-xl backdrop-blur-md">
                        <div class="flex items-center gap-3 border-b border-zinc-800/60 pb-4 mb-6">
                            <div class="bg-blue-500/10 p-2 rounded-lg text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-white uppercase tracking-wide">Payment</h3>
                        </div>
                        
                        <div class="mb-8 relative">
                            <select name="metode_pembayaran" required class="w-full px-4 py-3.5 bg-zinc-950/50 border border-zinc-800 rounded-xl focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 text-white transition-all text-sm font-medium appearance-none cursor-pointer">
                                <option value="" disabled {{ old('metode_pembayaran') ? '' : 'selected' }}>Select Payment Method</option>
                                @foreach($bankAccounts as $method)
                                    <option value="{{ $method }}" {{ old('metode_pembayaran') === $method ? 'selected' : '' }} class="bg-zinc-900">
                                        {{ $method }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-zinc-500">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>

                        <button type="submit" 
                                {{ $hasOutOfStock ? 'disabled' : '' }}
                                class="w-full bg-white hover:bg-zinc-200 text-zinc-950 font-bold py-4 px-4 rounded-xl transition-all duration-300 text-sm tracking-widest uppercase shadow-[0_0_20px_rgba(255,255,255,0.1)] hover:shadow-[0_0_25px_rgba(255,255,255,0.2)] disabled:opacity-50 disabled:cursor-not-allowed">
                            Complete Order
                        </button>
                        
                        <div class="text-center mt-6">
                            <a href="{{ route('cart.index') }}" class="text-zinc-500 hover:text-white text-xs font-semibold transition-colors underline underline-offset-4 decoration-zinc-700 hover:decoration-white uppercase tracking-wider">
                                &larr; Back to Cart
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <footer class="mt-16 border-t border-zinc-800/60 bg-zinc-950 pt-12 pb-8 relative z-10">
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
        document.addEventListener('DOMContentLoaded', function() {
            const kurirSelect = document.getElementById('kurir-select');
            const shippingFeeEl = document.getElementById('shipping-fee');
            const shippingInsuranceEl = document.getElementById('shipping-insurance');
            const totalCostEl = document.getElementById('total-cost');
            const totalItemPriceEl = document.getElementById('total-item-price');
            
            const formatRupiah = (number) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            };

            function updateSummary() {
                const totalItemPrice = parseInt(totalItemPriceEl.getAttribute('data-value')) || 0;
                const selectedOption = kurirSelect.options[kurirSelect.selectedIndex];
                const ongkir = parseInt(selectedOption.getAttribute('data-ongkir')) || 0;
                
                const asuransi = parseInt(selectedOption.getAttribute('data-asuransi')) || 0;
                
                const totalCost = totalItemPrice + ongkir + asuransi;
                
                shippingFeeEl.textContent = formatRupiah(ongkir);
                shippingInsuranceEl.textContent = formatRupiah(asuransi); 
                totalCostEl.textContent = formatRupiah(totalCost);
            }
            
            kurirSelect.addEventListener('change', updateSummary);
            
            updateSummary();
        });
    </script>
</body>
</html>