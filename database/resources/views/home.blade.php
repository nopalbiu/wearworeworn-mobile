<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WearWoreWorn - Catalog</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground min-h-screen">

    <nav class="bg-gray-800 border-b border-gray-700 p-4 sticky top-0 z-50 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <a href="/" class="bg-white text-black font-bold text-xl px-4 py-1 uppercase rounded-sm tracking-wider">
                    LOGO
                </a>
            </div>
            
            <div class="hidden md:flex space-x-8 font-semibold text-gray-300">
                <a href="/" class="hover:text-white transition">CATALOG</a>
                <a href="#" class="hover:text-white transition">ABOUT US</a>
            </div>

            <div class="flex items-center space-x-4">
                <a href="{{ route('cart.index') }}" class="text-gray-300 hover:text-white transition relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </a>

                @auth
                    <a href="{{ route('home') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded transition">Account</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white font-semibold transition">Log In</a>
                    <a href="{{ route('register') }}" class="bg-white hover:bg-gray-200 text-black font-bold px-4 py-2 rounded transition">Sign Up</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4 mt-8">
        <div class="bg-gray-800 rounded-lg py-20 px-8 text-center mb-8 border border-gray-700 shadow-md">
            <h1 class="text-4xl font-bold text-white mb-4">HERO SECTION</h1>
            <p class="text-gray-400 max-w-2xl mx-auto">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </p>
        </div>

        <div class="flex flex-col md:flex-row gap-8">
            <form id="filter-form" action="{{ route('home') }}" method="GET" class="w-full md:w-1/4">
                
                <div class="bg-gray-800 p-4 rounded border border-gray-700 mb-6">
                    <div class="flex">
                        <div class="bg-gray-700 p-2 rounded-l flex items-center justify-center border-y border-l border-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Ketik untuk mencari..." class="w-full p-2 bg-gray-700 text-white placeholder-gray-400 rounded-r border-y border-r border-gray-600 focus:outline-none focus:border-gray-500">
                    </div>
                </div>

                <div class="bg-gray-800 p-4 rounded border border-gray-700 mb-6 text-gray-300">
                    <h3 class="font-bold text-lg mb-3 text-white uppercase">CATEGORIES</h3>
                    @foreach($categories as $category)
                    <label class="flex items-center space-x-2 mb-2 cursor-pointer">
                        <input type="checkbox" name="category[]" value="{{ $category->id_category }}" {{ in_array($category->id_category, (array)request('category', [])) ? 'checked' : '' }} class="filter-checkbox form-checkbox text-blue-600 bg-gray-700 border-gray-600 rounded">
                        <span>{{ $category->nama_category }}</span>
                    </label>
                    @endforeach
                </div>

                <div class="bg-gray-800 p-4 rounded border border-gray-700 mb-6 text-gray-300">
                    <h3 class="font-bold text-lg mb-3 text-white uppercase">SIZE</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($sizes as $size)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="size[]" value="{{ $size->id_size }}" {{ in_array($size->id_size, (array)request('size', [])) ? 'checked' : '' }} class="filter-checkbox peer hidden">
                            <span class="inline-block px-3 py-1 rounded border border-gray-600 text-gray-300 bg-gray-700 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-gray-600 transition-colors">
                                {{ $size->nama_size }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-gray-800 p-4 rounded border border-gray-700 mb-6 text-gray-300">
                    <h3 class="font-bold text-lg mb-3 text-white uppercase">PRICE RANGE</h3>
                    <label class="flex items-center space-x-2 mb-2 cursor-pointer">
                        <input type="checkbox" name="price[]" value="0-200000" {{ in_array('0-200000', (array)request('price', [])) ? 'checked' : '' }} class="filter-checkbox price-checkbox form-checkbox text-blue-600 bg-gray-700 border-gray-600 rounded">
                        <span>Di bawah Rp 200.000</span>
                    </label>
                    <label class="flex items-center space-x-2 mb-2 cursor-pointer">
                        <input type="checkbox" name="price[]" value="200000-300000" {{ in_array('200000-300000', (array)request('price', [])) ? 'checked' : '' }} class="filter-checkbox price-checkbox form-checkbox text-blue-600 bg-gray-700 border-gray-600 rounded">
                        <span>Rp 200.000 - Rp 300.000</span>
                    </label>
                    <label class="flex items-center space-x-2 mb-2 cursor-pointer">
                        <input type="checkbox" name="price[]" value="300000-9999999" {{ in_array('300000-9999999', (array)request('price', [])) ? 'checked' : '' }} class="filter-checkbox price-checkbox form-checkbox text-blue-600 bg-gray-700 border-gray-600 rounded">
                        <span>Di atas Rp 300.000</span>
                    </label>
                </div>
            </form>

            <div class="w-full md:w-3/4">
                
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h2 class="text-2xl font-bold text-white">PRODUCTS</h2>
                    
                    <div class="flex items-center space-x-2">
                        <label for="sort-select" class="text-gray-400 font-semibold">SORT BY:</label>
                        <select name="sort" id="sort-select" form="filter-form" class="bg-gray-800 text-white border border-gray-600 rounded p-2 focus:outline-none focus:border-gray-400 cursor-pointer">
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Termurah</option>
                            <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Termahal</option>
                        </select>
                    </div>
                </div>

                <div id="ajax-product-area">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        @forelse($products as $product)
                        
                        <a href="{{ route('product.show', $product->nama_product) }}" class="block bg-gray-800 rounded overflow-hidden border border-gray-700 shadow-lg hover:border-gray-500 transition duration-300 group cursor-pointer">
                            
                            <div class="bg-gray-700 h-48 flex items-center justify-center border-b border-gray-700 relative overflow-hidden">
                                <img src="{{ asset('images/' . ($product->images->first()->url_gambar ?? 'placeholder.png')) }}" onerror="this.onerror=null; this.src='https://dummyimage.com/400x400/374151/fff&text=No+Image';" alt="{{ $product->nama_product }}" class="h-full w-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                            </div>
                            
                            <div class="p-4 bg-gray-800">
                                <h3 class="font-bold text-base text-white mb-1 truncate group-hover:text-blue-400 transition-colors duration-300" title="{{ $product->nama_product }}">{{ $product->nama_product }}</h3>
                                <p class="text-gray-400 font-semibold text-sm">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                            </div>

                        </a>

                        @empty
                        <div class="col-span-4 text-center py-12">
                            <p class="text-gray-400 text-lg">Produk yang dicari tidak ditemukan.</p>
                        </div>
                        @endforelse
                    </div>

                    <div class="mt-6 flex justify-center">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filter-form');
            const searchInput = document.getElementById('search-input');
            const sortSelect = document.getElementById('sort-select');
            const ajaxArea = document.getElementById('ajax-product-area');
            const priceCheckboxes = document.querySelectorAll('.price-checkbox');
            
            let typingTimer;

            const fetchProducts = (url) => {
                ajaxArea.style.opacity = '0.5'; 
                ajaxArea.style.pointerEvents = 'none'; 

                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => {
                    if (!response.ok) throw new Error('Aduh, ada error di backend!');
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
                    console.error(error);
                    ajaxArea.style.opacity = '1';
                    ajaxArea.style.pointerEvents = 'auto';
                });
            };

            const updateKatalog = () => {
                const formData = new FormData(form);
                if (!formData.has('sort')) formData.append('sort', sortSelect.value);
                const searchParams = new URLSearchParams(formData);
                fetchProducts('/?' + searchParams.toString());
            };

            // Trigger Search dengan Delay 1 Detik
            searchInput.addEventListener('input', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(updateKatalog, 1000); 
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

            // Mencegah Paginasi pindah halaman penuh (Ubah jadi AJAX)
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