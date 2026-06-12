<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - WearWoreWorn</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen font-sans selection:bg-blue-900/50 selection:text-blue-100 flex flex-col">

    <nav class="bg-zinc-950/80 backdrop-blur-md border-b border-zinc-800/60 p-4 sticky top-0 z-50 shadow-sm relative">
        <div class="container mx-auto flex justify-between items-center relative">
            
            <a href="/" class="flex items-center transition-transform duration-300 hover:scale-105 relative z-10">
                <img src="{{ asset('images/logo-www.png') }}" alt="WearWoreWorn Logo" class="h-8 w-auto object-contain">
            </a>
            
            <div class="hidden md:flex space-x-12 font-medium text-sm tracking-widest text-zinc-400 absolute left-1/2 transform -translate-x-1/2 w-max">
                <a href="/" class="hover:text-blue-400 transition-colors">CATALOG</a>
                <a href="{{ route('about') }}" class="text-zinc-100 hover:text-blue-400 transition-colors drop-shadow-[0_0_8px_rgba(96,165,250,0.3)]">ABOUT US</a>
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

    <main class="flex-grow container mx-auto p-4 lg:p-8 mt-4 max-w-4xl">
        
        <div class="text-center my-16 relative group">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-blue-600/5 rounded-full blur-[80px] pointer-events-none"></div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">OUR STORY</h1>
            <div class="h-1 w-12 bg-blue-500 mx-auto rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]"></div>
        </div>

        <div class="space-y-12 text-zinc-400 text-lg leading-relaxed">
            
            <section class="bg-zinc-900/20 rounded-2xl p-8 border border-zinc-900 shadow-sm backdrop-blur-sm">
                <p class="mb-6">
                    Established with a vision to redefine everyday wardrobe essentials, <span class="text-white font-semibold">WearWoreWorn</span> blends contemporary subcultures with premium textile craftsmanship. We sit at the intersection of casual comfort and high-street aesthetics, developing garments tailored for modern narrative expressions.
                </p>
                <p>
                    Every garment is systematically engineered to transition through different stages of style evolution. We reject transient fads, focusing instead on timeless engineering, structured drops, and durable structural silhouettes that naturally adapt to the culture of the streets.
                </p>
            </section>

            <section class="text-center py-8 border-t border-zinc-900">
                <h2 class="text-xl font-semibold text-zinc-200 mb-3 tracking-wide">METICULOUSLY CRAFTED</h2>
                <p class="text-base text-zinc-500 max-w-xl mx-auto">
                    From raw heavy cotton selections to reinforced double-stitch construction, we ensure our collections maintain precise structural parameters.
                </p>
            </section>

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

</body>
</html>