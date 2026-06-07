<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Auth; 

class AuthenticationController extends Controller
{

    public function showRegister() 
    {
        return view('authentication.register'); 
    }

    public function processRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', 
            'password' => 'required|min:6'
        ]);

        $user = new User();
        $user->nama = $request->name; 
        $user->email = $request->email;
        $user->password = Hash::make($request->password); 
        $user->id_role = 2; 

        $user->save(); 

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    public function showLogin()
    {
        return view('authentication.login');
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang kamu masukkan salah.',
        ]);
    }


    public function showAdminLogin()
    {
        // Pastikan anak frontend nanti bikin file admin_login.blade.php di folder authentication ya
        return view('authentication.admin_login'); 
    }

    public function processAdminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->id_role == 1) {
                $request->session()->regenerate();
                return redirect()->route('admin.katalog'); 
            }

            Auth::logout();
            return back()->withErrors([
                'email' => 'Akses ditolak! Halaman ini hanya diperuntukkan bagi Akun Admin.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Email atau password admin salah.',
        ]);
    }
}