<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.variant.product']);

        if ($request->filled('status')) {
            $query->where('status_pesanan', $request->status);
        }

        $sortId = $request->get('sort_id', 'desc');
        if (in_array($sortId, ['asc', 'desc'])) {
            $query->orderBy('id_order', $sortId);
        } else {
            $query->orderBy('tanggal_order', 'desc');
        }

        $orders = $query->paginate(20)->appends($request->all());

        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pesanan' => 'required|in:menunggu pembayaran,sudah dibayar,dalam perjalanan,selesai'
        ]);

        $order = Order::findOrFail($id);
        
        $order->update([
            'status_pesanan' => $request->status_pesanan
        ]);

        return redirect()->route('admin.orders.index')
                         ->with('success', 'Status pesanan berhasil diperbarui!');
    }
}