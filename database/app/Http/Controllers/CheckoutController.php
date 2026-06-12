<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    private const COURIER_OPTIONS = [
        'JNE'     => ['ongkir' => 15000, 'asuransi' => 3000],
        'J&T'     => ['ongkir' => 13000, 'asuransi' => 2500],
        'SiCepat' => ['ongkir' => 12000, 'asuransi' => 2000],
    ];

    private const BANK_ACCOUNTS = [
        'Transfer Bank BCA'     => 'BCA 1234567890 a.n. WearWoreWorn',
        'Transfer Bank Mandiri' => 'Mandiri 0987654321 a.n. WearWoreWorn',
        'Transfer Bank BRI'     => 'BRI 1122334455 a.n. WearWoreWorn',
    ];

    public function index(Request $request): View|RedirectResponse
    {
        $user = Auth::user();

        $selectedItems = $request->input('items', []);
        if (empty($selectedItems) || !is_array($selectedItems)) {
            return redirect()->route('cart.index')
                ->with('error', 'Silakan pilih setidaknya satu barang untuk di-checkout.');
        }

        $cart = Cart::with(['items.variant.product.images', 'items.variant.size'])
                    ->where('id_user', $user->id_user)
                    ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja kosong.');
        }

        $cart->setRelation('items', $cart->items->filter(function($item) use ($selectedItems) {
            return in_array($item->id_cart_item, $selectedItems);
        }));

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Barang yang dipilih tidak valid atau sudah dihapus.');
        }

        $totalItemPrice = 0;
        $hasOutOfStock = false;

        foreach ($cart->items as $item) {
            $totalItemPrice += $item->qty * (float) $item->variant->product->harga;
            if ($item->variant->stok < $item->qty) {
                $hasOutOfStock = true;
            }
        }

        return view('checkout.index', [
            'cart'            => $cart,
            'user'            => $user,
            'totalItemPrice'  => $totalItemPrice,
            'courierOptions'  => self::COURIER_OPTIONS,
            'bankAccounts'    => array_keys(self::BANK_ACCOUNTS),
            'hasOutOfStock'   => $hasOutOfStock,
            'selectedItems'   => $selectedItems,
        ]);
    }

    public function processOrder(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_penerima'      => 'required|string|max:255',
            'no_hp'              => 'required|string|regex:/^[0-9]+$/|min:11|max:20',
            'alamat'             => 'required|string|max:1000',
            'kurir'              => 'required|string|in:JNE,J&T,SiCepat',
            'metode_pembayaran'  => 'required|string',
            'items'              => 'required|array',
            'items.*'            => 'exists:cart_items,id_cart_item',
        ]);

        $user = Auth::user();

        $cart = Cart::with(['items.variant.product', 'items.variant.size'])
                    ->where('id_user', $user->id_user)
                    ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja kosong.');
        }

        $selectedItems = $request->input('items', []);

        $cart->setRelation('items', $cart->items->filter(function($item) use ($selectedItems) {
            return in_array($item->id_cart_item, $selectedItems);
        }));

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Barang yang dipilih tidak valid.');
        }

        foreach ($cart->items as $item) {
            $variant = $item->variant;
            if ($variant->stok < $item->qty) {
                $productName = $variant->product->nama_product;
                $sizeName = $variant->size->nama_size;
                return redirect()->route('cart.index')
                    ->with('error', "Stok tidak cukup untuk {$productName} (Size: {$sizeName}). Stok tersedia: {$variant->stok}, diminta: {$item->qty}.");
            }
        }

        $kurir = $request->input('kurir');
        $courierData = self::COURIER_OPTIONS[$kurir];

        $totalItemPrice = 0;
        foreach ($cart->items as $item) {
            $totalItemPrice += $item->qty * (float) $item->variant->product->harga;
        }

        $ongkir   = $courierData['ongkir'];
        $asuransi = $courierData['asuransi'];
        $totalHarga = $totalItemPrice + $ongkir + $asuransi;

        $alamatPengiriman = "Nama: {$request->input('nama_penerima')}\n"
                          . "No. HP: {$request->input('no_hp')}\n"
                          . "Alamat: {$request->input('alamat')}";

        $order = DB::transaction(function () use ($user, $cart, $alamatPengiriman, $kurir, $totalHarga, $request, $selectedItems) {
            $order = Order::create([
                'id_user'           => $user->id_user,
                'tanggal_order'     => now(),
                'alamat_pengiriman' => $alamatPengiriman,
                'kurir'             => $kurir,
                'total_harga'       => $totalHarga,
                'status_pesanan'    => 'menunggu_pembayaran',
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'id_order'     => $order->id_order,
                    'id_variant'   => $item->variant->id_variant,
                    'qty'          => $item->qty,
                    'harga_satuan' => $item->variant->product->harga,
                ]);

                $item->variant->decrement('stok', $item->qty);
            }

            Payment::create([
                'id_order'           => $order->id_order,
                'metode_pembayaran'  => $request->input('metode_pembayaran'),
                'status_pembayaran'  => 'belum_bayar',
                'jumlah_bayar'       => $totalHarga,
                'waktu_transaksi'    => now(),
            ]);

            \App\Models\CartItem::whereIn('id_cart_item', $selectedItems)->delete();

            return $order;
        });

        return redirect()->route('checkout.success', $order->id_order);
    }

    public function success($orderId): View
    {
        $user = Auth::user();

        $order = Order::with(['payment', 'items.variant.product'])
                      ->where('id_user', $user->id_user)
                      ->where('id_order', $orderId)
                      ->firstOrFail();

        $infoRekening = self::BANK_ACCOUNTS[$order->payment->metode_pembayaran]
                        ?? 'BCA 1234567890 a.n. WearWoreWorn';

        $batasPembayaran = \Carbon\Carbon::parse($order->tanggal_order)->addHours(24);

        return view('checkout.success', [
            'order'            => $order,
            'infoRekening'     => $infoRekening,
            'batasPembayaran'  => $batasPembayaran,
        ]);
    }
}
