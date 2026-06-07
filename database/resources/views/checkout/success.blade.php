<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - WearWoreWorn</title>
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

    <main class="flex-grow container mx-auto p-4 md:p-8 mt-4 flex items-center justify-center">
        <div class="bg-gray-800 rounded-lg p-10 border border-gray-700 shadow-2xl w-full max-w-xl text-center">
            
            <div class="flex justify-center mb-6">
                <div class="bg-green-900/40 rounded-full p-4 border border-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>

            <h1 class="text-3xl font-black text-white tracking-widest uppercase mb-2">Pesanan Berhasil!</h1>
            <p class="text-gray-400 font-medium mb-3">Terima kasih telah berbelanja di WearWoreWorn.</p>
            
            {{-- BADGE ORDER ID DITAMBAHKAN DI SINI --}}
            <div class="mb-8">
                <span class="inline-block bg-gray-900 text-gray-300 font-mono text-sm px-4 py-1.5 rounded border border-gray-700 shadow-inner">
                    ORDER ID: <strong class="text-white">#{{ $order->id_order }}</strong>
                </span>
            </div>

            <div class="bg-gray-700 rounded p-6 mb-8 border border-gray-600">
                <p class="text-sm text-gray-400 uppercase tracking-wider mb-2 font-semibold">Total Transfer</p>
                <h2 class="text-4xl font-bold text-white mb-6">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</h2>
                
                <div class="bg-gray-800 rounded px-4 py-4 border border-dashed border-gray-500 flex justify-center items-center space-x-2">
                    <span class="text-gray-300 font-mono tracking-wide text-lg font-bold">
                        {{ $infoRekening }}
                    </span>
                </div>
            </div>

            <div class="mb-8">
                <span class="inline-block bg-yellow-900/50 text-yellow-500 text-xs font-bold px-3 py-1 rounded-full border border-yellow-700 uppercase tracking-widest mb-4">
                    Menunggu Pembayaran...
                </span>
                
                <p class="text-gray-400 text-sm mb-1">Batas waktu pembayaran:</p>
                <strong id="countdown-timer" class="text-xl font-bold text-red-400 tracking-wide">Menghitung...</strong>
            </div>

            <div class="bg-gray-700/40 rounded-lg p-6 mb-8 text-left border border-gray-600">
                <h3 class="text-white font-bold mb-4 uppercase tracking-wide border-b border-gray-600 pb-2 text-sm">Langkah Selanjutnya:</h3>
                <ol class="list-decimal list-inside text-gray-300 space-y-3 text-sm font-medium">
                    <li>Transfer senilai <strong class="text-white">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong> ke rekening di atas.</li>
                    <li>Simpan bukti transfer (screenshot m-banking atau foto struk).</li>
                    <li>Klik tombol WhatsApp di bawah untuk mengirim bukti transfer ke Admin.</li>
                </ol>
            </div>

            @php
                $waNumber = "6285877539320"; 
                $orderId = $order->id_order;
                $totalFormatted = number_format($order->total_harga, 0, ',', '.');
                
                $waText = "Hi Admin WearWoreWorn!%0A%0ABerikut adalah konfirmasi pembayaran untuk pesanan saya:%0A- Order ID: " . $orderId . " %0A- Total: Rp " . $totalFormatted . " %0ABukti transfer sudah saya lampirkan bersama pesan ini. Mohon bantuannya untuk segera diproses, thank you! %0A(notes: jangan lupa attach foto bukti transfermu sebelum klik kirim)";
                $waLink = "https://wa.me/{$waNumber}?text={$waText}";
            @endphp

            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ $waLink }}" target="_blank" class="w-full sm:w-auto px-6 py-3 bg-[#25D366] text-white font-bold rounded hover:bg-[#128C7E] transition-colors uppercase tracking-wider shadow-md flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.286 1.264.457 1.694.585.712.227 1.36.195 1.871.118.571-.086 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    <span>Konfirmasi WA</span>
                </a>
                
                <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3 bg-gray-600 text-white font-bold rounded hover:bg-gray-500 transition-colors uppercase tracking-wider shadow-md text-center">
                    Beranda
                </a>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deadline = {{ $batasPembayaran->getTimestamp() * 1000 }};
            const timerElement = document.getElementById('countdown-timer');

            const x = setInterval(function() {
                const now = new Date().getTime();
                const distance = deadline - now;

                if (distance < 0) {
                    clearInterval(x);
                    timerElement.innerHTML = "WAKTU HABIS";
                    timerElement.classList.replace('text-red-400', 'text-gray-500');
                    return;
                }

                const hours = Math.floor(distance / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                const displayHours = hours.toString().padStart(2, '0');
                const displayMinutes = minutes.toString().padStart(2, '0');
                const displaySeconds = seconds.toString().padStart(2, '0');

                timerElement.innerHTML = displayHours + " Jam " + displayMinutes + " Menit " + displaySeconds + " Detik";
                
            }, 1000);
        });
    </script>
</body>
</html>