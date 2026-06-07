<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Khusus Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen m-0 font-sans">

    <main class="bg-white p-10 rounded-xl shadow-lg w-full max-w-sm border border-gray-200">
        
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold mb-1 text-black">Log In</h1>
            <h2 class="text-lg font-bold uppercase tracking-widest text-gray-600">ADMIN</h2>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-sm text-sm" role="alert">
                <p>{{ $errors->first() }}</p>
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST" class="flex flex-col gap-5">
            @csrf 
            
            <div class="flex flex-col gap-2">
                <label for="email" class="font-semibold text-sm uppercase text-gray-800">Email Admin</label>
                <input type="email" name="email" id="email" required 
                    class="p-3 bg-gray-100 border border-gray-200 rounded-md text-sm outline-none focus:ring-2 focus:ring-gray-400 transition-all">
            </div>
            
            <div class="flex flex-col gap-2">
                <label for="password" class="font-semibold text-sm uppercase text-gray-800">Password</label>
                <input type="password" name="password" id="password" required 
                    class="p-3 bg-gray-100 border border-gray-200 rounded-md text-sm outline-none focus:ring-2 focus:ring-gray-400 transition-all">
            </div>
            
            <button type="submit" 
                class="bg-[#555555] hover:bg-[#333333] text-white py-3 px-6 text-base font-bold rounded-md cursor-pointer mt-4 w-full transition duration-200">
                Masuk
            </button>
        </form>
        
    </main>

</body>
</html>