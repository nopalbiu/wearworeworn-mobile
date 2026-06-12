<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WearWoreWorn - Catalog</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .custom-pagination nav {
            justify-content: flex-end !important;
        }
        .custom-pagination p {
            color: #a1a1aa !important;
        }/
        .custom-pagination span.bg-white, 
        .custom-pagination a.bg-white,
        .custom-pagination span.relative.inline-flex, 
        .custom-pagination a.relative.inline-flex {
            background-color: #18181b !important;
            border-color: #27272a !important;
            color: #d4d4d8 !important;
            transition: all 0.3s ease;
        }
        .custom-pagination a.relative.inline-flex:hover {
            background-color: #27272a !important;
            color: #60a5fa !important;
            border-color: #3f3f46 !important;
        }
        .custom-pagination span[aria-current="page"] > span {
            background-color: rgba(30, 58, 138, 0.4) !important;
            border-color: #3b82f6 !important;
            color: #93c5fd !important;
            font-weight: bold;
        }
        .custom-pagination span[aria-disabled="true"] > span {
            background-color: #09090b !important;
            color: #52525b !important;
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen font-sans selection:bg-blue-900/50 selection:text-blue-100 flex flex-col">

    <nav class="bg-zinc-950/80 backdrop-blur-md border-b border-zinc-800/60 p-4 sticky top-0 z-50 shadow-sm relative">
        <div class="container mx-auto flex justify-between items-center relative">
            
            <a href="/" class="flex items-center transition-transform duration-300 hover:scale-105 relative z-10">
                <img src="{{ asset('images/logo-www.png') }}" alt="WearWoreWorn Logo" class="h-8 w-auto object-contain">
            </a>
            
            <div class="hidden md:flex space-x-12 font-medium text-sm tracking-widest text-zinc-400 absolute left-1/2 transform -translate-x-1/2 w-max">
                <a href="/" class="text-zinc-100 hover:text-blue-400 transition-colors drop-shadow-[0_0_8px_rgba(96,165,250,0.3)]">CATALOG</a>
                <a href="{{ route('about') }}" class="hover:text-blue-400 transition-colors">ABOUT US</a>
            </div>

            <div class="flex items-center space-x-6 relative z-10">
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

    <main class="flex-grow container mx-auto p-4 lg:p-8 mt-4">
        
        <div class="relative bg-gradient-to-br from-zinc-900 via-blue-950/10 to-zinc-950 rounded-2xl py-24 px-8 text-center mb-12 border border-zinc-800/60 shadow-[0_0_40px_rgba(30,58,138,0.03)] overflow-hidden group">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03]"></div>
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-600/10 rounded-full blur-[100px] group-hover:bg-blue-600/20 transition-colors duration-700 pointer-events-none"></div>
            <div class="relative z-10">
                <h1 class="text-5xl md:text-6xl font-extrabold text-white mb-6 tracking-tight drop-shadow-md">WEARWOREWORN</h1>
                <p class="text-zinc-400 max-w-2xl mx-auto text-lg leading-relaxed">
                    Elevate your everyday style with our latest streetwear collection. Designed for comfort, built for the streets.
                </p>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-10">
            
            <form id="filter-form" action="{{ route('home') }}" method="GET" class="w-full lg:w-1/4 flex flex-col gap-6">
                
                <div class="bg-zinc-900/40 p-5 rounded-xl border border-zinc-800/80 backdrop-blur-sm shadow-sm">
                    <div class="flex items-center bg-zinc-950/50 rounded-lg border border-zinc-800 focus-within:border-blue-500/50 focus-within:ring-1 focus-within:ring-blue-500/30 transition-all overflow-hidden shadow-inner">
                        <div class="pl-3 pr-2 py-2 text-zinc-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Search articles..." class="w-full py-3 bg-transparent text-white placeholder-zinc-600 text-sm focus:outline-none">
                    </div>
                </div>

                <div class="bg-zinc-900/40 p-6 rounded-xl border border-zinc-800/80 shadow-sm">
                    <h3 class="font-bold text-xs tracking-widest text-zinc-500 mb-4 uppercase">Categories</h3>
                    <div class="flex flex-col gap-3">
                        @foreach($categories as $category)
                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="category[]" value="{{ $category->id_category }}" {{ in_array($category->id_category, (array)request('category', [])) ? 'checked' : '' }} class="filter-checkbox peer h-5 w-5 cursor-pointer appearance-none rounded border border-zinc-700 bg-zinc-950/50 checked:border-blue-500 checked:bg-blue-600/20 hover:border-blue-400 transition-all">
                                <span class="absolute text-blue-400 opacity-0 peer-checked:opacity-100 pointer-events-none top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" stroke="currentColor" stroke-width="1">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                            </div>
                            <span class="text-sm text-zinc-400 group-hover:text-zinc-200 transition-colors">{{ $category->nama_category }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-zinc-900/40 p-6 rounded-xl border border-zinc-800/80 shadow-sm">
                    <h3 class="font-bold text-xs tracking-widest text-zinc-500 mb-4 uppercase">Size</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($sizes as $size)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="size[]" value="{{ $size->id_size }}" {{ in_array($size->id_size, (array)request('size', [])) ? 'checked' : '' }} class="filter-checkbox peer hidden">
                            <span class="inline-flex items-center justify-center min-w-[2.5rem] px-3 py-1.5 rounded-md border border-zinc-700 text-xs font-medium text-zinc-400 bg-zinc-950/50 peer-checked:bg-blue-900/40 peer-checked:text-blue-300 peer-checked:border-blue-500 hover:border-zinc-500 transition-all shadow-sm">
                                {{ $size->nama_size }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-zinc-900/40 p-6 rounded-xl border border-zinc-800/80 shadow-sm">
                    <h3 class="font-bold text-xs tracking-widest text-zinc-500 mb-4 uppercase">Price Range</h3>
                    <div class="flex flex-col gap-3">
                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="price[]" value="0-200000" {{ in_array('0-200000', (array)request('price', [])) ? 'checked' : '' }} class="filter-checkbox price-checkbox peer h-5 w-5 cursor-pointer appearance-none rounded-full border border-zinc-700 bg-zinc-950/50 checked:border-blue-500 checked:bg-blue-600/20 hover:border-blue-400 transition-all">
                                <span class="absolute w-2 h-2 rounded-full bg-blue-400 opacity-0 peer-checked:opacity-100 pointer-events-none top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 shadow-[0_0_5px_rgba(96,165,250,0.8)]"></span>
                            </div>
                            <span class="text-sm text-zinc-400 group-hover:text-zinc-200 transition-colors">Under Rp 200.000</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="price[]" value="200000-300000" {{ in_array('200000-300000', (array)request('price', [])) ? 'checked' : '' }} class="filter-checkbox price-checkbox peer h-5 w-5 cursor-pointer appearance-none rounded-full border border-zinc-700 bg-zinc-950/50 checked:border-blue-500 checked:bg-blue-600/20 hover:border-blue-400 transition-all">
                                <span class="absolute w-2 h-2 rounded-full bg-blue-400 opacity-0 peer-checked:opacity-100 pointer-events-none top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 shadow-[0_0_5px_rgba(96,165,250,0.8)]"></span>
                            </div>
                            <span class="text-sm text-zinc-400 group-hover:text-zinc-200 transition-colors">Rp 200.000 - Rp 300.000</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="price[]" value="300000-9999999" {{ in_array('300000-9999999', (array)request('price', [])) ? 'checked' : '' }} class="filter-checkbox price-checkbox peer h-5 w-5 cursor-pointer appearance-none rounded-full border border-zinc-700 bg-zinc-950/50 checked:border-blue-500 checked:bg-blue-600/20 hover:border-blue-400 transition-all">
                                <span class="absolute w-2 h-2 rounded-full bg-blue-400 opacity-0 peer-checked:opacity-100 pointer-events-none top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 shadow-[0_0_5px_rgba(96,165,250,0.8)]"></span>
                            </div>
                            <span class="text-sm text-zinc-400 group-hover:text-zinc-200 transition-colors">Over Rp 300.000</span>
                        </label>
                    </div>
                </div>
            </form>

            <div class="w-full lg:w-3/4">
                
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 border-b border-zinc-800/60 pb-4">
                    <h2 class="text-xl font-semibold text-white tracking-wide">NEW ARRIVALS</h2>
                    
                    <div class="flex items-center space-x-3">
                        <label for="sort-select" class="text-zinc-500 text-xs font-bold tracking-widest uppercase">Sort By:</label>
                        <div class="relative">
                            <select name="sort" id="sort-select" form="filter-form" class="bg-zinc-950 text-sm text-zinc-300 border border-zinc-700/80 rounded-lg pl-3 pr-8 py-2 appearance-none focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 cursor-pointer transition-all shadow-sm">
                                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Latest Release</option>
                                <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Lowest Price</option>
                                <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Highest Price</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-zinc-500">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="ajax-product-area" class="transition-opacity duration-300">
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-x-6 gap-y-10 mb-10">
                        @forelse($products as $product)
                        
                        <a href="{{ route('product.show', $product->nama_product) }}" class="group block cursor-pointer rounded-xl transition-all duration-300 hover:shadow-[0_0_30px_rgba(30,58,138,0.15)] bg-zinc-900/20 border border-transparent hover:border-blue-900/30 p-2">
                            <div class="relative bg-zinc-900 rounded-lg overflow-hidden aspect-[4/5] mb-4">
                                @php
                                    $firstImage = $product->images->first();
                                    if ($firstImage) {
                                        $imgUrl = str_starts_with($firstImage->url_gambar, 'http') ? $firstImage->url_gambar : asset('storage/' . $firstImage->url_gambar);
                                    } else {
                                        $imgUrl = 'https://dummyimage.com/600x800/27272a/fff&text=No+Image';
                                    }
                                @endphp
                                <img src="{{ $imgUrl }}" onerror="this.onerror=null; this.src='https://dummyimage.com/600x800/27272a/fff&text=No+Image';" alt="{{ $product->nama_product }}" class="h-full w-full object-cover object-center transform group-hover:scale-105 transition-transform duration-700 ease-out">
                                <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            
                            <div class="flex flex-col px-1 pb-1">
                                <h3 class="font-medium text-zinc-200 text-base truncate group-hover:text-blue-400 transition-colors duration-300" title="{{ $product->nama_product }}">{{ $product->nama_product }}</h3>
                                <p class="text-zinc-500 mt-1 text-sm tracking-wide group-hover:text-zinc-400 transition-colors">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                            </div>
                        </a>

                        @empty
                        <div class="col-span-1 sm:col-span-2 xl:col-span-3 flex flex-col items-center justify-center py-24 bg-zinc-900/20 rounded-2xl border border-zinc-800/50 border-dashed">
                            <div class="bg-zinc-900 p-4 rounded-full mb-4 shadow-inner">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                            <p class="text-zinc-400 text-sm font-medium">No products matched your filters.</p>
                            <button onclick="document.getElementById('filter-form').reset(); updateKatalog();" class="mt-4 text-xs text-blue-500 hover:text-blue-400 underline underline-offset-4 transition-colors">Clear all filters</button>
                        </div>
                        @endforelse
                    </div>

                    <div class="flex justify-end mt-4 custom-pagination">
                        {{ $products->links() }}
                    </div>
                </div>
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
            const form = document.getElementById('filter-form');
            const searchInput = document.getElementById('search-input');
            const sortSelect = document.getElementById('sort-select');
            const ajaxArea = document.getElementById('ajax-product-area');
            const priceCheckboxes = document.querySelectorAll('.price-checkbox');
            
            let typingTimer;

            const fetchProducts = (url) => {
                ajaxArea.style.opacity = '0.3'; 
                ajaxArea.style.pointerEvents = 'none'; 

                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.text();
                })
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    ajaxArea.innerHTML = doc.getElementById('ajax-product-area').innerHTML;
                    
                    ajaxArea.style.opacity = '1';
                    ajaxArea.style.pointerEvents = 'auto';
                    window.history.pushState({}, '', url);
                })
                .catch(error => {
                    ajaxArea.style.opacity = '1';
                    ajaxArea.style.pointerEvents = 'auto';
                });
            };

            window.updateKatalog = () => {
                const formData = new FormData(form);
                if (!formData.has('sort')) formData.append('sort', sortSelect.value);
                const searchParams = new URLSearchParams(formData);
                fetchProducts('/?' + searchParams.toString());
            };

            searchInput.addEventListener('input', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(updateKatalog, 800); 
            });

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                updateKatalog();
            });

            document.addEventListener('change', function(e) {
                if (e.target.id === 'sort-select') {
                    updateKatalog();
                }
                
                if (e.target.classList.contains('filter-checkbox')) {
                    if (e.target.classList.contains('price-checkbox') && e.target.checked) {
                        priceCheckboxes.forEach(cb => {
                            if (cb !== e.target) cb.checked = false;
                        });
                    }
                    updateKatalog();
                }
            });

            document.addEventListener('click', function(e) {
                const link = e.target.closest('nav[role="navigation"] a');
                if (link) {
                    e.preventDefault();
                    fetchProducts(link.href);
                }
            });
        });
    </script>
</body>
</html>