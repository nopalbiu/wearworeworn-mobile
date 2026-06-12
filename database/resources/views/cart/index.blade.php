<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - WearWoreWorn</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen font-sans selection:bg-blue-900/50 selection:text-blue-100 flex flex-col relative">

    <div class="fixed top-20 left-0 -ml-32 w-96 h-96 bg-blue-600/5 rounded-full blur-[120px] pointer-events-none"></div>

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

    <main class="flex-grow container mx-auto p-4 md:p-8 mt-4 relative z-10 max-w-4xl">
        
        <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2 tracking-tight uppercase">Shopping Cart</h1>
        <p class="text-zinc-500 text-sm mb-8">Review the items in your cart before proceeding to checkout.</p>
        
        @if(session('success'))
        <div class="bg-green-900/20 border border-green-500/50 text-green-400 px-5 py-4 rounded-xl mb-8 flex items-center gap-3 shadow-sm backdrop-blur-sm flash-msg transition-all duration-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="font-medium text-sm tracking-wide">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-900/20 border border-red-500/50 text-red-400 px-5 py-4 rounded-xl mb-8 flex items-center gap-3 shadow-sm backdrop-blur-sm flash-msg transition-all duration-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span class="font-medium text-sm tracking-wide">{{ session('error') }}</span>
        </div>
        @endif

        @if($cart && $cart->items->count() > 0)
            @php 
                $grandTotal = 0; 
                $hasOutOfStock = false;
            @endphp
            
            <form action="{{ route('checkout.index') }}" method="GET" id="checkout-form">
                
                <div class="space-y-4">
                    @foreach($cart->items as $item)
                        @php 
                            $subtotal = $item->qty * $item->variant->product->harga;
                            $grandTotal += $subtotal;
                            $isOutOfStock = $item->variant->stok < $item->qty;
                            if ($isOutOfStock) $hasOutOfStock = true;
                        @endphp
                        
                        <div class="bg-zinc-900/40 border {{ $isOutOfStock ? 'border-red-900/50 bg-red-900/5' : 'border-zinc-800/80 hover:border-zinc-700/80' }} rounded-2xl p-4 sm:p-5 flex items-center gap-4 sm:gap-6 relative shadow-sm backdrop-blur-md transition-colors group">
                            
                            {{-- Checkbox --}}
                            <div class="flex-shrink-0">
                                <input type="checkbox" name="items[]" value="{{ $item->id_cart_item }}" data-price="{{ $subtotal }}" class="item-checkbox w-5 h-5 rounded border-zinc-700 bg-zinc-950/50 checked:bg-blue-500 checked:border-blue-500 focus:ring-1 focus:ring-blue-500/50 cursor-pointer transition-all" {{ $isOutOfStock ? 'disabled' : 'checked' }}>
                            </div>

                            {{-- Badge Stok Habis --}}
                            @if($isOutOfStock)
                            <div class="absolute top-3 right-3 sm:top-4 sm:right-4">
                                <span class="bg-red-900/80 border border-red-500/50 text-red-200 text-[10px] sm:text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                                    @if($item->variant->stok <= 0)
                                        Stok Habis
                                    @else
                                        Sisa {{ $item->variant->stok }}
                                    @endif
                                </span>
                            </div>
                            @endif

                            {{-- Thumbnail --}}
                            <div class="w-20 h-24 sm:w-24 sm:h-28 bg-zinc-900 rounded-xl overflow-hidden flex-shrink-0 border border-zinc-800/60 p-1 {{ $isOutOfStock ? 'opacity-50 grayscale-[50%]' : '' }}">
                                @php
                                    $firstImg = $item->variant->product->images->first();
                                    if ($firstImg) {
                                        $imgUrl = str_starts_with($firstImg->url_gambar, 'http') ? $firstImg->url_gambar : asset('storage/' . $firstImg->url_gambar);
                                    } else {
                                        $imgUrl = 'https://dummyimage.com/150x150/27272a/fff&text=No+Image';
                                    }
                                @endphp
                                <img src="{{ $imgUrl }}" 
                                     class="w-full h-full object-cover rounded-lg"
                                     onerror="this.onerror=null; this.src='https://dummyimage.com/150x150/27272a/fff&text=No+Img';">
                            </div>
                            
                            {{-- Info produk --}}
                            <div class="flex-grow {{ $isOutOfStock ? 'opacity-50' : '' }}">
                                <a href="{{ route('product.show', $item->variant->product->nama_product) }}" class="text-zinc-100 font-bold text-base sm:text-lg hover:text-blue-400 transition-colors line-clamp-2 pr-12 sm:pr-20 mb-1">
                                    {{ $item->variant->product->nama_product }}
                                </a>
                                <p class="text-zinc-400 text-xs sm:text-sm mb-1">Size: <span class="text-zinc-300 font-semibold">{{ $item->variant->size->nama_size }}</span></p>
                                <p class="text-zinc-400 text-xs sm:text-sm">Qty: <span class="text-zinc-300 font-semibold">{{ $item->qty }}</span></p>
                            </div>
                            
                            {{-- Harga --}}
                            <div class="text-right flex-shrink-0 hidden sm:block {{ $isOutOfStock ? 'opacity-50' : '' }}">
                                <p class="text-zinc-500 text-xs sm:text-sm mb-1">Rp {{ number_format($item->variant->product->harga, 0, ',', '.') }} × {{ $item->qty }}</p>
                                <p class="text-white font-bold text-lg">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                            </div>
                            
                            {{-- Harga Mobile (Tampil jika layar kecil) --}}
                            <div class="absolute bottom-4 left-[6.5rem] sm:hidden {{ $isOutOfStock ? 'opacity-50' : '' }}">
                                <p class="text-white font-bold text-base">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                            </div>

                            {{-- Tombol Delete --}}
                            <div class="absolute bottom-2 right-2 sm:static sm:bottom-auto sm:right-auto sm:ml-4">
                                <button type="button" onclick="event.preventDefault(); document.getElementById('delete-item-{{ $item->id_cart_item }}').submit();" class="text-zinc-600 hover:text-red-400 p-2 rounded-lg hover:bg-red-400/10 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 bg-zinc-900/60 border border-zinc-800/80 rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row justify-between items-center shadow-lg backdrop-blur-md">
                    <span class="text-lg sm:text-xl font-bold text-zinc-400 uppercase tracking-widest mb-2 sm:mb-0">Total Price</span>
                    <span id="grand-total-display" class="text-2xl sm:text-3xl font-extrabold text-blue-400">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
                
                <div class="mt-6">
                    <button type="submit" id="btn-buy" class="block w-full bg-white hover:bg-zinc-200 text-zinc-950 font-bold py-4 px-4 rounded-xl transition-all duration-300 text-sm tracking-widest uppercase text-center shadow-[0_0_20px_rgba(255,255,255,0.1)] hover:shadow-[0_0_25px_rgba(255,255,255,0.2)] disabled:opacity-50 disabled:cursor-not-allowed" onclick="if(document.querySelectorAll('input[name=\'items[]\']:checked').length === 0) { alert('Pilih setidaknya satu barang untuk di-checkout.'); return false; }">
                        Checkout Selected Items
                    </button>
                </div>
            </form>

            @foreach($cart->items as $item)
            <form id="delete-item-{{ $item->id_cart_item }}" action="{{ route('cart.remove', $item->id_cart_item) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
            @endforeach

        @else
            <div class="flex flex-col items-center justify-center py-20 px-4 bg-zinc-900/20 rounded-3xl border border-zinc-800/50 border-dashed w-full mx-auto mt-10">
                <div class="bg-zinc-900/80 p-6 rounded-full mb-6 shadow-inner border border-zinc-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2 tracking-wide">Shopping Cart is Empty</h2>
                <p class="text-zinc-500 text-center mb-8 max-w-md text-sm">It looks like you haven't added any items to your cart. Come see our latest collection!</p>
                <a href="/" class="bg-white hover:bg-zinc-200 text-zinc-950 font-bold py-3.5 px-8 rounded-xl transition-all duration-300 text-sm tracking-widest uppercase shadow-[0_0_15px_rgba(255,255,255,0.1)] hover:shadow-[0_0_20px_rgba(255,255,255,0.2)]">
                    Start Shopping
                </a>
            </div>
        @endif
    </main>

    <footer class="mt-auto border-t border-zinc-800/60 bg-zinc-950 pt-12 pb-8 relative z-10">
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
            // Auto-hide flash messages
            const flashMsgs = document.querySelectorAll('.flash-msg');
            flashMsgs.forEach(msg => {
                setTimeout(() => {
                    msg.style.opacity = '0';
                    msg.style.transform = 'translateY(-10px)';
                    setTimeout(() => msg.remove(), 500);
                }, 4000);
            });

            const checkboxes = document.querySelectorAll('.item-checkbox');
            const grandTotalDisplay = document.getElementById('grand-total-display');
            const btnBuy = document.getElementById('btn-buy');

            const formatRupiah = (number) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            };

            function calculateTotal() {
                let currentTotal = 0;
                let checkedCount = 0;

                checkboxes.forEach(cb => {
                    if (cb.checked && !cb.disabled) {
                        currentTotal += parseInt(cb.getAttribute('data-price')) || 0;
                        checkedCount++;
                    }
                });

                if(grandTotalDisplay) {
                    grandTotalDisplay.textContent = formatRupiah(currentTotal);
                }
                
                if(btnBuy) {
                    btnBuy.disabled = checkedCount === 0;
                }
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', calculateTotal);
            });

            if(checkboxes.length > 0) calculateTotal();
        });
    </script>
</body>
</html>