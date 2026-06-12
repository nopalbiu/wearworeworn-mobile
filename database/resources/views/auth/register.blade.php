<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - WearWoreWorn</title>
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
                    <a href="{{ route('register') }}" class="bg-white hover:bg-zinc-200 text-zinc-950 text-sm font-bold px-5 py-2 rounded-full transition-all shadow-[0_0_15px_rgba(255,255,255,0.1)] hover:shadow-[0_0_20px_rgba(255,255,255,0.2)] drop-shadow-[0_0_8px_rgba(255,255,255,0.3)]">Sign Up</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center p-4 relative z-10 mt-10 mb-10">
        
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-blue-600/10 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="relative w-full max-w-md">
            <div class="bg-zinc-900/40 p-8 sm:p-10 rounded-2xl shadow-2xl border border-zinc-800/80 backdrop-blur-md">
                
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('images/logo-www.png') }}" alt="WearWoreWorn Logo" class="h-10 w-auto opacity-90">
                </div>

                <h1 class="text-3xl font-extrabold text-center mb-8 text-white tracking-tight">Create Account</h1>
                
                @if($errors->any())
                    <div class="bg-red-900/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-xl mb-6 text-sm flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label for="name" class="block text-xs font-bold tracking-widest text-zinc-500 uppercase mb-2">Full Name</label>
                        <input type="text" name="name" id="name" required placeholder="Enter your full name" 
                            class="w-full px-4 py-3 bg-zinc-950/50 border border-zinc-800 rounded-xl focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 text-white placeholder-zinc-600 transition-all text-sm">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold tracking-widest text-zinc-500 uppercase mb-2">Email</label>
                        <input type="email" name="email" id="email" required placeholder="Enter your email" 
                            class="w-full px-4 py-3 bg-zinc-950/50 border border-zinc-800 rounded-xl focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 text-white placeholder-zinc-600 transition-all text-sm">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-xs font-bold tracking-widest text-zinc-500 uppercase mb-2">Password</label>
                        <input type="password" name="password" id="password" required placeholder="••••••••" 
                            class="w-full px-4 py-3 bg-zinc-950/50 border border-zinc-800 rounded-xl focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 text-white placeholder-zinc-600 transition-all text-sm">
                    </div>

                    <div class="text-center pt-3">
                        <p class="text-zinc-500 text-sm">Already have an account? 
                            <a href="{{ route('login') }}" class="text-zinc-300 hover:text-white font-semibold transition-colors underline underline-offset-4 decoration-zinc-700 hover:decoration-white">Log In</a>
                        </p>
                    </div>
                    
                    <button type="submit" class="w-full bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 hover:border-zinc-500 text-white font-bold py-3.5 px-4 rounded-xl mt-2 transition-all duration-300 shadow-lg text-sm tracking-widest uppercase">
                        Sign Up
                    </button>
                </form>
            </div>
        </div>
    </main>

    <footer class="mt-auto border-t border-zinc-800/60 bg-zinc-950 pt-12 pb-8">
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