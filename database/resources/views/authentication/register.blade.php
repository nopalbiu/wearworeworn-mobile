<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - WearWoreWorn</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white flex flex-col min-h-screen">

    <nav class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex justify-between items-center w-full">
        <div class="flex items-center">
            <a href="/" class="bg-white text-black font-bold text-xl px-4 py-1 uppercase rounded-sm tracking-wider">
                LOGO
            </a>
        </div>
        
        <div class="hidden md:flex space-x-8">
            <a href="/catalog" class="text-gray-300 hover:text-white font-semibold transition duration-200">CATALOG</a>
            <a href="/about" class="text-gray-300 hover:text-white font-semibold transition duration-200">ABOUT US</a>
        </div>

        <div class="flex items-center space-x-4">
            <a href="/cart" class="text-gray-400 hover:text-white transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
            </a>
            
            <a href="{{ route('register') }}" class="text-gray-300 hover:text-white font-medium text-sm px-3 py-2 transition duration-200">Sign Up</a>
            <a href="{{ route('login') }}" class="bg-gray-700 hover:bg-gray-600 border border-gray-600 text-white px-4 py-2 rounded-lg font-medium text-sm transition duration-200">Log In</a>
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center p-4">
        <div class="bg-gray-800 p-8 rounded-xl shadow-lg w-full max-w-md border border-gray-700">
            <h1 class="text-3xl font-bold text-center mb-6 text-white">Buat Akun</h1>
            
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-400">Nama Lengkap</label>
                    <input type="text" name="name" id="name" required 
                        class="mt-1 w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-400">Email</label>
                    <input type="email" name="email" id="email" required 
                        class="mt-1 w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-400">Password</label>
                    <input type="password" name="password" id="password" required 
                        class="mt-1 w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white">
                </div>

                <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 mt-4">
                    Daftar Sekarang
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-400">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Masuk di sini</a>
            </p>
        </div>
    </main>

</body>
</html>