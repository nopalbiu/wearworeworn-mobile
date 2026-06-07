<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $orders = Order::where('id_user', $user->id_user)
                        ->with(['items.variant.product'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('user.profile', compact('user', 'orders'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        
        $user->nama = $request->nama;
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
    
}