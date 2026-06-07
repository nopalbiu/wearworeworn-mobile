<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - WearWoreWorn</title>
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
        <h1 class="text-3xl font-extrabold text-white mb-8">Keranjang Belanja</h1>
        {{-- Flash message --}}
        @if(session('success'))
        <div class="bg-green-900/60 border border-green-500 text-green-200 px-5 py-3 rounded-lg mb-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-900/60 border border-red-500 text-red-200 px-5 py-3 rounded-lg mb-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
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
                        <div class="bg-gray-800 border {{ $isOutOfStock ? 'border-red-500/50' : 'border-gray-700' }} rounded-lg p-5 flex items-center gap-6 relative">
                            
                            {{-- Checkbox untuk memilih barang --}}
                            <div class="flex-shrink-0">
                                <input type="checkbox" name="items[]" value="{{ $item->id_cart_item }}" class="w-6 h-6 rounded border-gray-600 bg-gray-700 focus:ring-blue-500" {{ $isOutOfStock ? 'disabled' : '' }}>
                            </div>

                            {{-- Badge Stok Habis --}}
                            @if($isOutOfStock)
                            <div class="absolute top-3 right-3">
                                <span class="bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase">
                                    @if($item->variant->stok <= 0)
                                        Stok Habis
                                    @else
                                        Stok Sisa {{ $item->variant->stok }}
                                    @endif
                                </span>
                            </div>
                            @endif
                            {{-- Thumbnail --}}
                            <div class="w-20 h-20 bg-gray-700 rounded overflow-hidden flex-shrink-0 {{ $isOutOfStock ? 'opacity-50' : '' }}">
                                @if($item->variant->product->images->first())
                                    <img src="{{ asset('images/' . $item->variant->product->images->first()->url_gambar) }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null; this.src='https://dummyimage.com/80x80/4b5563/fff&text=No+Img';">
                                @else
                                    <img src="https://dummyimage.com/80x80/4b5563/fff&text=No+Img" class="w-full h-full object-cover">
                                @endif
                            </div>
                            {{-- Info produk --}}
                            <div class="flex-grow {{ $isOutOfStock ? 'opacity-50' : '' }}">
                                <h3 class="text-white font-bold text-lg">{{ $item->variant->product->nama_product }}</h3>
                                <p class="text-gray-400 text-sm">Size: {{ $item->variant->size->nama_size }}</p>
                                <p class="text-gray-400 text-sm">Qty: {{ $item->qty }}</p>
                            </div>
                            {{-- Harga --}}
                            <div class="text-right flex-shrink-0 {{ $isOutOfStock ? 'opacity-50' : '' }}">
                                <p class="text-gray-400 text-sm">Rp {{ number_format($item->variant->product->harga, 0, ',', '.') }} × {{ $item->qty }}</p>
                                <p class="text-white font-bold text-lg">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                            </div>
                            
                            {{-- Tombol Delete --}}
                            <div class="ml-4">
                                <button type="button" onclick="event.preventDefault(); document.getElementById('delete-item-{{ $item->id_cart_item }}').submit();" class="text-red-500 hover:text-red-400 p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- Total (Akan perlu JS untuk menghitung ulang secara live saat checkbox dicentang, untuk saat ini hanya menampilkan total keseluruhan keranjang) --}}
                <div class="mt-8 bg-gray-800 border border-gray-700 rounded-lg p-6 flex justify-between items-center">
                    <span class="text-xl font-bold text-white">Total Keranjang</span>
                    <span class="text-2xl font-extrabold text-white">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
                {{-- Tombol Buy --}}
                <div class="mt-6">
                    <button type="submit" id="btn-buy" class="block w-full bg-white hover:bg-gray-200 text-black font-bold py-4 px-4 rounded transition duration-200 text-lg text-center" onclick="if(document.querySelectorAll('input[name=\'items[]\']:checked').length === 0) { alert('Pilih setidaknya satu barang untuk di-checkout.'); return false; }">
                        Checkout Terpilih
                    </button>
                </div>
            </form>

            {{-- Hidden Forms for Deletion --}}
            @foreach($cart->items as $item)
            <form id="delete-item-{{ $item->id_cart_item }}" action="{{ route('cart.remove', $item->id_cart_item) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
            @endforeach
        @else
            <div class="text-center py-20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-gray-600 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-gray-400 text-xl mb-6">Keranjang belanja kamu masih kosong.</p>
                <a href="{{ route('home') }}" class="inline-block bg-white hover:bg-gray-200 text-black font-bold py-3 px-8 rounded transition">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </main>
</body>
</html>
