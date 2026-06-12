<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->nama_product }} - WearWoreWorn</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen font-sans selection:bg-blue-900/50 selection:text-blue-100 flex flex-col">

    <nav class="bg-zinc-950/80 backdrop-blur-md border-b border-zinc-800/60 p-4 sticky top-0 z-50 shadow-sm relative mb-8">
        <div class="container mx-auto flex items-center justify-between md:grid md:grid-cols-3 relative">
            
            <div class="flex justify-start">
                <a href="/" class="flex items-center transition-transform duration-300 hover:scale-105 relative z-10">
                    <img src="{{ asset('images/logo-www.png') }}" alt="WearWoreWorn Logo" class="h-8 w-auto object-contain">
                </a>
            </div>
            
            <div class="hidden md:flex justify-center space-x-12 font-medium text-sm tracking-widest text-zinc-400 w-full relative z-10">
                <a href="/" class="text-zinc-100 hover:text-blue-400 transition-colors drop-shadow-[0_0_8px_rgba(96,165,250,0.3)]">CATALOG</a>
                <a href="{{ route('about') }}" class="hover:text-blue-400 transition-colors">ABOUT US</a>
            </div>

            <div class="flex justify-end items-center space-x-6 relative z-10">
                <a href="{{ route('cart.index') }}" class="text-zinc-400 hover:text-blue-400 transition-colors relative group">
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

    <main class="flex-grow container mx-auto p-4 md:p-8 lg:px-12 mt-4 max-w-7xl">

        <div class="mb-8 text-xs font-bold tracking-widest uppercase text-zinc-500">
            <a href="/" class="hover:text-blue-400 transition-colors">Home</a> 
            <span class="mx-2">/</span> 
            <span class="text-zinc-300">{{ $product->nama_product }}</span>
        </div>

        {{-- Flash message sukses --}}
        @if(session('success'))
        <div class="bg-zinc-900/80 border border-green-500/50 text-green-400 px-5 py-4 rounded-xl mb-8 flex items-center gap-3 shadow-lg backdrop-blur-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="font-medium text-sm tracking-wide">{{ session('success') }}</span>
        </div>
        @endif

        {{-- Flash message error --}}
        @if($errors->any())
        <div class="bg-zinc-900/80 border border-red-500/50 text-red-400 px-5 py-4 rounded-xl mb-8 flex items-center gap-3 shadow-lg backdrop-blur-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span class="font-medium text-sm tracking-wide">{{ $errors->first() }}</span>
        </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-12 lg:gap-16">
            
            <div class="w-full lg:w-1/2 flex flex-col gap-5">
                <div class="bg-zinc-900/30 rounded-2xl aspect-[4/5] sm:aspect-square flex items-center justify-center overflow-hidden border border-zinc-800/60 shadow-[0_0_40px_rgba(0,0,0,0.3)] relative group p-2">
                    <div class="absolute inset-0 bg-gradient-to-tr from-blue-900/5 to-transparent pointer-events-none"></div>
                    
                    {{-- Pengecekan Cloudinary untuk Gambar Utama --}}
                    @php
                        $firstImg = $product->images->first();
                        if ($firstImg) {
                            $mainImage = str_starts_with($firstImg->url_gambar, 'http') ? $firstImg->url_gambar : asset('storage/' . $firstImg->url_gambar);
                        } else {
                            $mainImage = 'https://dummyimage.com/800x800/27272a/fff&text=No+Image';
                        }
                    @endphp
                    
                    <img id="main-image" src="{{ $mainImage }}" onerror="this.onerror=null; this.src='https://dummyimage.com/800x800/27272a/fff&text=No+Image';" class="w-full h-full object-cover rounded-xl transition-transform duration-700 group-hover:scale-105">
                </div>
                
                <div class="grid grid-cols-5 gap-3 sm:gap-4">
                    {{-- Loop Gambar Tambahan --}}
                    @foreach($product->images as $index => $image)
                    @php
                        $imgUrl = str_starts_with($image->url_gambar, 'http') ? $image->url_gambar : asset('storage/' . $image->url_gambar);
                    @endphp
                    <button type="button" 
                            class="thumbnail-btn bg-zinc-900/50 rounded-xl aspect-square border {{ $index === 0 ? 'border-zinc-300 shadow-[0_0_10px_rgba(255,255,255,0.1)]' : 'border-zinc-800/80' }} hover:border-zinc-500 overflow-hidden transition-all p-1" 
                            data-src="{{ $imgUrl }}">
                        <img src="{{ $imgUrl }}" 
                             onerror="this.onerror=null; this.src='https://dummyimage.com/150x150/27272a/fff&text=Broken';" 
                             class="w-full h-full object-cover rounded-lg">
                    </button>
                    @endforeach

                    {{-- Size Chart --}}
                    @if($product->url_size_chart)
                    @php
                        $sizeChartUrl = str_starts_with($product->url_size_chart, 'http') ? $product->url_size_chart : asset('storage/' . $product->url_size_chart);
                    @endphp
                    <button type="button" 
                            class="thumbnail-btn bg-zinc-900/50 rounded-xl aspect-square border border-zinc-800/80 hover:border-zinc-500 overflow-hidden relative transition-all p-1" 
                            data-src="{{ $sizeChartUrl }}"
                            title="Size Chart">
                        <img src="{{ $sizeChartUrl }}" 
                             onerror="this.onerror=null; this.src='https://dummyimage.com/150x150/27272a/fff&text=Broken';" 
                             class="w-full h-full object-cover rounded-lg opacity-50 hover:opacity-100 transition-opacity">
                        <span class="absolute inset-0 flex items-center justify-center text-white font-bold text-xs tracking-widest bg-zinc-950/60 pointer-events-none rounded-lg m-1 backdrop-blur-[2px]">SIZE</span>
                    </button>
                    @endif
                </div>
            </div>

            <div class="w-full lg:w-1/2 flex flex-col justify-start pt-2">
                <form id="cart-form" action="{{ route('cart.add', $product->id_product) }}" method="POST">
                    @csrf
                    
                    <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 uppercase tracking-tight drop-shadow-md">{{ $product->nama_product }}</h1>
                    <p class="text-2xl font-semibold text-zinc-400 mb-10 tracking-wide">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>

                    <div class="mb-8">
                        <div class="flex justify-between items-end mb-4">
                            <span class="text-xs font-bold tracking-widest text-zinc-500 uppercase">Select Size</span>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            @forelse($variants as $variant)
                            <label class="cursor-pointer">
                                <input type="radio" name="size" value="{{ $variant->nama_size }}" class="peer hidden size-radio">
                                <span class="inline-flex items-center justify-center min-w-[3.5rem] h-12 px-4 rounded-xl border border-zinc-700 text-zinc-400 bg-zinc-900/50 peer-checked:bg-blue-900/40 peer-checked:text-blue-300 peer-checked:border-blue-500 hover:border-zinc-500 transition-all font-bold text-base shadow-sm">
                                    {{ $variant->nama_size }}
                                </span>
                            </label>
                            @empty
                            <span class="text-red-400/80 font-medium text-sm bg-red-900/20 px-4 py-2 rounded-lg border border-red-900/50">Varian ukuran belum tersedia.</span>
                            @endforelse
                        </div>
                        @error('size')
                        <p class="text-red-400 text-sm mt-3 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="mb-10 flex flex-wrap items-center gap-6">
                        <div class="flex items-center border border-zinc-700/80 rounded-xl bg-zinc-900/50 h-12 w-36 shadow-inner overflow-hidden">
                            <button type="button" id="btn-minus" class="w-1/3 h-full flex items-center justify-center text-zinc-500 hover:text-white hover:bg-zinc-800 transition-colors font-bold text-xl">-</button>
                            <input type="number" id="qty-input" name="quantity" value="1" min="1" max="99" class="w-1/3 h-full text-center bg-transparent text-white font-bold focus:outline-none text-lg" readonly>
                            <button type="button" id="btn-plus" class="w-1/3 h-full flex items-center justify-center text-zinc-500 hover:text-white hover:bg-zinc-800 transition-colors font-bold text-xl">+</button>
                        </div>
                        <div class="text-zinc-500 text-sm tracking-wide bg-zinc-900/30 px-4 py-2.5 rounded-lg border border-zinc-800/60">
                            Available Stock: <span id="stock-display" class="font-bold text-zinc-200 ml-1">{{ $totalStock }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4 mb-12 border-b border-zinc-800/60 pb-12">
                        @auth
                            <button type="submit" id="btn-add-to-cart" class="w-full bg-white hover:bg-zinc-200 text-zinc-950 font-bold py-4 px-4 rounded-xl transition-all duration-300 text-sm tracking-widest uppercase shadow-[0_0_20px_rgba(255,255,255,0.1)] hover:shadow-[0_0_25px_rgba(255,255,255,0.2)] disabled:opacity-60 disabled:cursor-not-allowed">
                                Add to Cart
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="w-full bg-white hover:bg-zinc-200 text-zinc-950 font-bold py-4 px-4 rounded-xl transition-all duration-300 text-sm tracking-widest uppercase text-center block shadow-[0_0_20px_rgba(255,255,255,0.1)] hover:shadow-[0_0_25px_rgba(255,255,255,0.2)]">
                                Add to Cart
                            </a>
                        @endauth

                        @auth
                            <button type="submit" id="btn-buy-now" formaction="{{ route('cart.buyNow', $product->id_product) }}" class="w-full bg-zinc-900 hover:bg-zinc-800 border border-zinc-700/80 hover:border-zinc-500 text-white font-bold py-4 px-4 rounded-xl transition-all duration-300 text-sm tracking-widest uppercase disabled:opacity-60 disabled:cursor-not-allowed">
                                Buy it now
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="w-full bg-zinc-900 hover:bg-zinc-800 border border-zinc-700/80 hover:border-zinc-500 text-white font-bold py-4 px-4 rounded-xl transition-all duration-300 text-sm tracking-widest uppercase text-center block">
                                Buy it now
                            </a>
                        @endauth
                    </div>

                    <div>
                        <h3 class="font-bold text-xs tracking-widest text-zinc-500 uppercase mb-4">Description</h3>
                        <p class="text-zinc-400 leading-relaxed text-sm md:text-base whitespace-pre-line">{{ $product->deskripsi }}</p>
                    </div>
                </form>
            </div>
        </div>
    </main>

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
        document.addEventListener('DOMContentLoaded', function() {
            const btnMinus    = document.getElementById('btn-minus');
            const btnPlus     = document.getElementById('btn-plus');
            const qtyInput    = document.getElementById('qty-input');
            const cartForm    = document.getElementById('cart-form');
            const btnAddToCart = document.getElementById('btn-add-to-cart');
            const btnBuyNow   = document.getElementById('btn-buy-now');
            const stockDisplay = document.getElementById('stock-display');

            // ================================================================
            // Data stok dari server
            // ================================================================
            const totalStock = {{ $totalStock }};
            const stockData = {
                @foreach($variants as $variant)
                    "{{ $variant->nama_size }}": {{ $variant->stok }},
                @endforeach
            };

            // ================================================================
            // Helpers: aktifkan / nonaktifkan tombol beli berdasarkan stok
            // ================================================================
            function setButtonsDisabled(disabled, reason) {
                [btnAddToCart, btnBuyNow].forEach(btn => {
                    if (!btn) return;
                    btn.disabled = disabled;
                    if (disabled) {
                        btn.title = reason || 'Tidak tersedia';
                        btn.classList.add('opacity-50', 'cursor-not-allowed');
                        btn.classList.remove('hover:bg-zinc-200', 'hover:bg-zinc-800');
                    } else {
                        btn.title = '';
                        btn.classList.remove('opacity-50', 'cursor-not-allowed');
                        btn.classList.add('hover:bg-zinc-200');
                    }
                });
            }

            // Cek stok awal
            if (totalStock <= 0) {
                setButtonsDisabled(true, 'Stok habis');
                if (btnAddToCart) btnAddToCart.textContent = 'Stok Habis';
                if (btnBuyNow) btnBuyNow.textContent = 'Stok Habis';
            }

            // ================================================================
            // Qty +/- (batasi sesuai stok ukuran yang dipilih)
            // ================================================================
            let currentMaxStock = totalStock;

            btnMinus && btnMinus.addEventListener('click', function() {
                let val = parseInt(qtyInput.value);
                if (val > 1) qtyInput.value = val - 1;
            });

            btnPlus && btnPlus.addEventListener('click', function() {
                let val = parseInt(qtyInput.value);
                if (val < Math.max(1, currentMaxStock)) qtyInput.value = val + 1;
            });

            // ================================================================
            // Ganti gambar dari thumbnail
            // ================================================================
            const thumbnails = document.querySelectorAll('.thumbnail-btn');
            const mainImage  = document.getElementById('main-image');

            thumbnails.forEach(btn => {
                btn.addEventListener('click', function() {
                    mainImage.src = this.getAttribute('data-src');
                    thumbnails.forEach(t => {
                        t.classList.remove('border-zinc-300', 'shadow-[0_0_10px_rgba(255,255,255,0.1)]');
                        t.classList.add('border-zinc-800/80');
                    });
                    this.classList.remove('border-zinc-800/80');
                    this.classList.add('border-zinc-300', 'shadow-[0_0_10px_rgba(255,255,255,0.1)]');
                });
            });

            // ================================================================
            // Pilihan ukuran
            // ================================================================
            const sizeRadios = document.querySelectorAll('.size-radio');
            let selectedRadio = null;

            sizeRadios.forEach(radio => {
                radio.addEventListener('click', function() {
                    // Toggle deselect
                    if (selectedRadio === this) {
                        this.checked = false;
                        selectedRadio = null;
                        currentMaxStock = totalStock;
                        stockDisplay.innerText = totalStock;

                        if (totalStock <= 0) {
                            setButtonsDisabled(true, 'Stok habis');
                        } else {
                            setButtonsDisabled(false);
                            if (btnAddToCart) btnAddToCart.textContent = 'Add to Cart';
                            if (btnBuyNow)    btnBuyNow.textContent    = 'Buy it now';
                        }
                        qtyInput.value = 1;
                        return;
                    }

                    selectedRadio = this;
                    const stok = stockData[this.value] !== undefined ? stockData[this.value] : 0;
                    currentMaxStock = stok;
                    stockDisplay.innerText = stok;

                    if (stok <= 0) {
                        setButtonsDisabled(true, 'Ukuran ini habis');
                        if (btnAddToCart) btnAddToCart.textContent = 'Stok Habis';
                        if (btnBuyNow)    btnBuyNow.textContent    = 'Stok Habis';
                        qtyInput.value = 0;
                    } else {
                        setButtonsDisabled(false);
                        if (btnAddToCart) btnAddToCart.textContent = 'Add to Cart';
                        if (btnBuyNow)    btnBuyNow.textContent    = 'Buy it now';
                        if (parseInt(qtyInput.value) > stok) qtyInput.value = stok;
                        if (parseInt(qtyInput.value) < 1)   qtyInput.value = 1;
                    }
                });
            });

            // ================================================================
            // Anti Double-Submit
            // ================================================================
            let isSubmitting = false;
            let lastClickedBtn = null;

            [btnAddToCart, btnBuyNow].forEach(btn => {
                if (btn) {
                    btn.addEventListener('click', function() {
                        lastClickedBtn = this;
                    });
                }
            });

            if (cartForm) {
                cartForm.addEventListener('submit', function(e) {
                    if (isSubmitting) {
                        e.preventDefault();
                        return;
                    }

                    isSubmitting = true;
                    const spinnerSvg = '<svg class="animate-spin h-4 w-4 inline-block mr-1.5 align-[-2px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';

                    if (lastClickedBtn === btnBuyNow) {
                        if (btnBuyNow) btnBuyNow.innerHTML = spinnerSvg + 'Processing...';
                    } else {
                        if (btnAddToCart) btnAddToCart.innerHTML = spinnerSvg + 'Adding...';
                    }

                    setTimeout(() => {
                        if (btnAddToCart) btnAddToCart.disabled = true;
                        if (btnBuyNow)    btnBuyNow.disabled    = true;
                    }, 50);
                });
            }

            // Auto-hide flash messages
            const flashMessages = document.querySelectorAll('[class*="border-green-500"], [class*="border-red-500"]');
            flashMessages.forEach(msg => {
                setTimeout(() => {
                    msg.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                    msg.style.opacity = '0';
                    msg.style.transform = 'translateY(-10px)';
                    setTimeout(() => msg.remove(), 600);
                }, 5000);
            });
        });
    </script>
</body>
</html>