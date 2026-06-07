<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - WearWoreWorn</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground min-h-screen flex flex-col">
    <nav class="bg-gray-800 border-b border-gray-700 p-4 sticky top-0 z-50 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <a href="/" class="bg-white text-black font-bold text-xl px-4 py-1 uppercase rounded-sm tracking-wider">LOGO</a>
            </div>
            <div class="hidden md:flex space-x-8 font-semibold text-gray-300">
                <a href="/" class="hover:text-white transition">CATALOG</a>
                <a href="#" class="hover:text-white transition">ABOUT US</a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('cart.index') }}" class="text-white transition relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </a>
                <a href="{{ route('home') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded transition">Account</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto p-4 md:p-8 mt-4">
        <div class="max-w-6xl mx-auto py-8 px-4">
            <h1 class="text-3xl font-black text-white tracking-widest mb-8 uppercase">Checkout</h1>

            @if($errors->any())
                <div class="bg-red-900/50 border border-red-500 text-red-200 px-6 py-4 rounded-lg mb-6 shadow-sm">
                    <ul class="list-disc list-inside space-y-1 text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($hasOutOfStock)
                <div class="bg-yellow-900/50 border border-yellow-500 text-yellow-200 px-6 py-4 rounded-lg mb-6 shadow-sm flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="font-bold text-sm">Beberapa item di keranjang memiliki stok yang tidak mencukupi. Silakan kembali ke keranjang.</p>
                </div>
            @endif

            <form action="{{ route('checkout.process') }}" method="POST" class="flex flex-col lg:flex-row gap-8">
                @csrf
                
                @foreach($selectedItems as $itemId)
                    <input type="hidden" name="items[]" value="{{ $itemId }}">
                @endforeach
                
                <div class="w-full lg:w-3/5 flex flex-col gap-6">
                    
                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 shadow-lg">
                        <h3 class="text-lg font-bold text-white mb-5 uppercase tracking-wide border-b border-gray-700 pb-3">Shipping Address</h3>
                        
                        <div class="space-y-5">
                            <div>
                                <label class="block text-gray-400 text-xs font-bold mb-2 uppercase">Name</label>
                                <input type="text" name="nama_penerima" value="{{ old('nama_penerima', $user->nama) }}" required 
                                       class="w-full bg-gray-700 text-white rounded p-3 focus:outline-none focus:ring-2 focus:ring-gray-500 border border-gray-600 transition-colors">
                            </div>
                            
                            <div>
                                <label class="block text-gray-400 text-xs font-bold mb-2 uppercase">No. Handphone</label>
                                <input type="text" name="no_hp" value="{{ old('no_hp') }}" required
                                       pattern="[0-9]{11,20}" title="Nomor handphone harus berupa angka dengan minimal 11 digit" 
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                       class="w-full bg-gray-700 text-white rounded p-3 focus:outline-none focus:ring-2 focus:ring-gray-500 border border-gray-600 transition-colors">
                            </div>
                            
                            <div>
                                <label class="block text-gray-400 text-xs font-bold mb-2 uppercase">Full Address</label>
                                <textarea name="alamat" rows="4" required 
                                          class="w-full bg-gray-700 text-white rounded p-3 focus:outline-none focus:ring-2 focus:ring-gray-500 border border-gray-600 transition-colors resize-none">{{ old('alamat') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 shadow-lg">
                        <h3 class="text-lg font-bold text-white mb-5 uppercase tracking-wide border-b border-gray-700 pb-3">Courier</h3>
                        
                        <div>
                            <select name="kurir" id="kurir-select" required class="w-full bg-gray-700 text-white font-medium rounded p-3 focus:outline-none focus:ring-2 focus:ring-gray-500 border border-gray-600 transition-colors appearance-none cursor-pointer">
                                <option value="" data-ongkir="0" data-asuransi="0" disabled {{ old('kurir') ? '' : 'selected' }}>Select Courier</option>
                                @foreach($courierOptions as $name => $data)
                                <option value="{{ $name }}" data-ongkir="{{ $data['ongkir'] }}" data-asuransi="{{ $data['asuransi'] }}" {{ old('kurir') === $name ? 'selected' : '' }}>
                                {{ $name }} - Rp {{ number_format($data['ongkir'], 0, ',', '.') }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-2/5 flex flex-col gap-6">
                    
                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 shadow-lg">
                        <h3 class="text-lg font-bold text-white mb-5 uppercase tracking-wide border-b border-gray-700 pb-3">Transaction Summary</h3>
                        
                        <div class="space-y-4 mb-6">
                            @foreach($cart->items as $item)
                            <div class="flex justify-between items-start text-sm">
                                <div class="text-gray-300 w-2/3 pr-2">
                                    <p class="font-bold truncate" title="{{ $item->variant->product->nama_product }}">
                                        {{ $item->variant->product->nama_product }}
                                    </p>
                                    <p class="text-gray-500 text-xs uppercase mt-0.5">Size: {{ $item->variant->size->nama_size }} &times; {{ $item->qty }}</p>
                                </div>
                                <div class="text-white font-medium whitespace-nowrap">
                                    Rp {{ number_format($item->qty * $item->variant->product->harga, 0, ',', '.') }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="border-t border-gray-600 pt-4 space-y-3 text-sm">
                            <div class="flex justify-between text-gray-400">
                                <span>Total Item Price</span>
                                <span id="total-item-price" data-value="{{ $totalItemPrice }}" class="text-white">Rp {{ number_format($totalItemPrice, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-400">
                                <span>Shipping Fee</span>
                                <span id="shipping-fee" class="text-white">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-gray-400">
                                <span>Shipping Insurance</span>
                                {{-- Script untuk kalkulasi harga realtime --}}
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
                const asuransi = ongkir > 0 ? 5000 : 0; 
                const totalCost = totalItemPrice + ongkir + asuransi;
                shippingFeeEl.textContent = formatRupiah(ongkir);
                shippingInsuranceEl.textContent = formatRupiah(asuransi);
                totalCostEl.textContent = formatRupiah(totalCost);
            }
            kurirSelect.addEventListener('change', updateSummary);
            updateSummary();
        });
    </script>
                                <span id="shipping-insurance" class="text-white">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center pt-4 mt-2 border-t border-gray-700">
                                <span class="text-white font-bold text-base uppercase">Total Cost</span>
                                <span id="total-cost" class="text-green-400 font-bold text-lg">Rp {{ number_format($totalItemPrice, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 shadow-lg">
                        <h3 class="text-lg font-bold text-white mb-5 uppercase tracking-wide border-b border-gray-700 pb-3">Payment Method</h3>
                        
                        <div class="mb-6">
                            <select name="metode_pembayaran" required class="w-full bg-gray-700 text-white font-medium rounded p-3 focus:outline-none focus:ring-2 focus:ring-gray-500 border border-gray-600 transition-colors appearance-none cursor-pointer">
                                <option value="" disabled {{ old('metode_pembayaran') ? '' : 'selected' }}>Select Payment Method</option>
                                @foreach($bankAccounts as $method)
                                    <option value="{{ $method }}" {{ old('metode_pembayaran') === $method ? 'selected' : '' }}>
                                        {{ $method }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" 
                                {{ $hasOutOfStock ? 'disabled' : '' }}
                                class="w-full bg-gray-600 text-white font-black py-4 rounded hover:bg-gray-500 transition-colors uppercase tracking-widest text-lg shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                            Pay Now
                        </button>
                        
                        <div class="text-center mt-6">
                            <a href="{{ route('cart.index') }}" class="text-gray-400 hover:text-white text-sm font-semibold transition-colors underline underline-offset-4">
                                &larr; Kembali ke Keranjang
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
</body>
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
</html>