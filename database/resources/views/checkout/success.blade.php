<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - WearWoreWorn</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen font-sans selection:bg-blue-900/50 flex flex-col">
    
    <nav class="bg-zinc-950/80 backdrop-blur-md border-b border-zinc-800/60 p-4 sticky top-0 z-50 shadow-sm">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="flex items-center transition-transform duration-300 hover:scale-105">
                <img src="{{ asset('images/logo-www.png') }}" alt="WearWoreWorn Logo" class="h-8 w-auto object-contain">
            </a>
            
            <div class="hidden md:flex space-x-12 font-medium text-sm tracking-widest text-zinc-400">
                <a href="/" class="hover:text-blue-400 transition-colors">CATALOG</a>
                <a href="{{ route('about') }}" class="hover:text-blue-400 transition-colors">ABOUT US</a>
            </div>

            <div class="flex items-center space-x-6">
                <a href="{{ route('user.profile') }}" class="bg-zinc-900 hover:bg-zinc-800 border border-zinc-700 hover:border-blue-500/50 text-white text-sm font-semibold px-5 py-2 rounded-full transition-all">Account</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto p-4 md:p-8 mt-4 flex items-center justify-center">
        <div class="relative bg-zinc-900/40 rounded-2xl p-10 border border-zinc-800/80 backdrop-blur-md shadow-[0_0_40px_rgba(30,58,138,0.05)] w-full max-w-xl text-center overflow-hidden group">
            
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-600/10 rounded-full blur-[100px] pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="flex justify-center mb-6">
                    <div class="bg-blue-600/10 rounded-full p-4 border border-blue-500/30 shadow-[0_0_20px_rgba(59,130,246,0.2)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-3xl font-extrabold text-white tracking-tight mb-2">PESANAN BERHASIL!</h1>
                <p class="text-zinc-400 font-medium mb-6">Terima kasih telah menjadi bagian dari culture WearWoreWorn.</p>
                
                <div class="mb-8">
                    <span class="inline-block bg-zinc-950/80 text-zinc-400 font-mono text-xs px-4 py-2 rounded-lg border border-zinc-800 shadow-inner tracking-widest">
                        ORDER ID: <strong class="text-blue-400">#{{ $order->id_order }}</strong>
                    </span>
                </div>

                <div class="bg-zinc-950/50 rounded-xl p-6 mb-8 border border-zinc-800 shadow-inner">
                    <p class="text-xs text-zinc-500 uppercase tracking-[0.2em] mb-2 font-bold">Total Transfer</p>
                    <h2 class="text-4xl font-bold text-white mb-6">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</h2>
                    
                    <div class="bg-zinc-900 rounded-lg px-4 py-4 border border-dashed border-zinc-700 flex justify-center items-center">
                        <span class="text-zinc-300 font-mono tracking-wider text-lg font-bold">
                            {{ $infoRekening }}
                        </span>
                    </div>
                </div>

                <div class="mb-8">
                    <div class="inline-flex items-center space-x-2 bg-blue-900/20 text-blue-300 text-[10px] font-bold px-3 py-1 rounded-full border border-blue-500/30 uppercase tracking-widest mb-4">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                        </span>
                        <span>Menunggu Pembayaran</span>
                    </div>
                    
                    <p class="text-zinc-500 text-xs mb-1">Batas waktu pembayaran:</p>
                    <strong id="countdown-timer" class="text-xl font-bold text-blue-400 tracking-tight">Menghitung...</strong>
                </div>

                <div class="bg-zinc-900/60 rounded-xl p-6 mb-8 text-left border border-zinc-800/80">
                    <h3 class="text-zinc-200 font-bold mb-4 uppercase tracking-widest text-[10px]">Langkah Selanjutnya:</h3>
                    <ul class="space-y-3 text-sm font-medium">
                        <li class="flex items-start gap-3">
                            <span class="text-blue-500 font-bold">01</span>
                            <span class="text-zinc-400">Transfer tepat <strong class="text-zinc-100 font-bold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-blue-500 font-bold">02</span>
                            <span class="text-zinc-400">Capture bukti transfer Anda.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-blue-500 font-bold">03</span>
                            <span class="text-zinc-400">Klik tombol di bawah untuk konfirmasi WhatsApp.</span>
                        </li>
                    </ul>
                </div>

                @php
                    $waNumber = "6285877539320"; 
                    $orderId = $order->id_order;
                    $totalFormatted = number_format($order->total_harga, 0, ',', '.');
                    $waText = "Hi Admin WearWoreWorn!%0A%0ABerikut adalah konfirmasi pembayaran untuk pesanan saya:%0A- Order ID: " . $orderId . " %0A- Total: Rp " . $totalFormatted . " %0ABukti transfer sudah saya lampirkan bersama pesan ini.";
                    $waLink = "https://wa.me/{$waNumber}?text={$waText}";
                @endphp

                <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a href="{{ $waLink }}" target="_blank" class="w-full sm:w-auto px-8 py-4 bg-white hover:bg-zinc-200 text-zinc-950 font-bold rounded-full transition-all shadow-[0_0_20px_rgba(255,255,255,0.1)] flex items-center justify-center gap-2 text-sm uppercase tracking-widest">
                        Konfirmasi WA
                    </a>
                    
                    <a href="{{ route('home') }}" class="w-full sm:w-auto px-8 py-4 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-full border border-zinc-700 transition-all text-sm uppercase tracking-widest text-center">
                        Beranda
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer class="mt-16 border-t border-zinc-800/60 bg-zinc-950 pt-12 pb-8">
        <div class="container mx-auto px-4 text-center">
            <p class="text-zinc-700 text-xs tracking-wider uppercase">&copy; 2026 WearWoreWorn. All rights reserved.</p>
        </div>
    </footer>

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
                    timerElement.classList.replace('text-blue-400', 'text-zinc-600');
                    return;
                }

                const hours = Math.floor(distance / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                timerElement.innerHTML = hours.toString().padStart(2, '0') + "h " + 
                                       minutes.toString().padStart(2, '0') + "m " + 
                                       seconds.toString().padStart(2, '0') + "s";
            }, 1000);
        });
    </script>
</body>
</html>