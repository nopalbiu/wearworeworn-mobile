<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Khusus Admin - WearWoreWorn</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-950 text-zinc-100 flex items-center justify-center min-h-screen m-0 font-sans antialiased selection:bg-blue-900/50 selection:text-blue-100">

    <main class="bg-zinc-900/40 backdrop-blur-md p-10 rounded-2xl border border-zinc-800/80 w-full max-w-md shadow-2xl relative overflow-hidden">
        
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-[2px] bg-gradient-to-r from-transparent via-blue-500/50 to-transparent"></div>
        
        <div class="text-center mb-8">
            <h1 class="text-4xl font-black tracking-tight mb-1 text-white uppercase">Log In</h1>
            <h2 class="text-xs font-bold uppercase tracking-[0.25em] text-blue-400">Admin Control</h2>
        </div>

        @if($errors->any())
            <div class="bg-red-950/40 border-l-4 border-red-500 text-red-400 p-4 mb-6 rounded-r-lg text-sm shadow-inner border border-y-red-900/30 border-r-red-900/30" role="alert">
                <p class="font-medium">{{ $errors->first() }}</p>
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST" class="flex flex-col gap-5">
            @csrf 
            
            <div class="flex flex-col gap-2">
                <label for="email" class="font-bold text-xs uppercase tracking-widest text-zinc-500">Email Admin</label>
                <input type="email" name="email" id="email" required 
                    class="p-3.5 bg-zinc-950/50 text-white border border-zinc-800/80 rounded-lg text-sm outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-all shadow-inner placeholder-zinc-700">
            </div>
            
            <div class="flex flex-col gap-2">
                <label for="password" class="font-bold text-xs uppercase tracking-widest text-zinc-500">Password</label>
                <input type="password" name="password" id="password" required 
                    class="p-3.5 bg-zinc-950/50 text-white border border-zinc-800/80 rounded-lg text-sm outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-all shadow-inner placeholder-zinc-700">
            </div>
            
            <button type="submit" 
                class="bg-white hover:bg-zinc-200 text-zinc-950 py-3.5 px-6 text-sm font-bold rounded-lg uppercase tracking-wider mt-4 w-full transition-all duration-300 shadow-[0_0_15px_rgba(255,255,255,0.1)] hover:shadow-[0_0_20px_rgba(255,255,255,0.2)]">
                Masuk
            </button>
        </form>
        
    </main>

</body>
</html>