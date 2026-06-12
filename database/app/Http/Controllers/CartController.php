<?php
namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
class CartController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $cart = Cart::with(['items.variant.product.images', 'items.variant.size'])
                    ->where('id_user', $user->id_user)
                    ->first();
        return view('cart.index', [
            'cart' => $cart,
        ]);
    }

    public function addToCart(Request $request, $productId): RedirectResponse
    {
        
        $request->validate([
            'size'     => 'required|string',
            'quantity' => 'required|integer|min:1|max:99',
        ]);
        
        $product = Product::findOrFail($productId);
        $size     = $request->input('size');
        $quantity = (int) $request->input('quantity', 1);
        
        $variant = $product->getVariantBySize($size);
        if ($variant->stok <= 0) {
            return redirect()->back()
                ->withErrors(['size' => "Ukuran {$size} tidak tersedia untuk produk ini."]);
        }
        $user = Auth::user();
        
        $cart = Cart::firstOrCreate(
            ['id_user' => $user->id_user]
        );
        
        $existingItem = CartItem::where('id_cart', $cart->id_cart)
                                ->where('id_variant', $variant->id_variant)
                                ->first();
        if ($existingItem) {
            
            $existingItem->qty += $quantity;
            $existingItem->save();
        } else {
        
            CartItem::create([
                'id_cart'    => $cart->id_cart,
                'id_variant' => $variant->id_variant,
                'qty'        => $quantity,
            ]);
        }
        return redirect()->back()
            ->with('success', "{$product->nama_product} (Size: {$size}) berhasil ditambahkan ke keranjang.");
    }
    public function buyNow(Request $request, $productId): RedirectResponse
    {
        $request->validate([
            'size'     => 'required|string',
            'quantity' => 'required|integer|min:1|max:99',
        ]);
        
        $product = Product::findOrFail($productId);
        $size     = $request->input('size');
        $quantity = (int) $request->input('quantity', 1);
        
        $variant = $product->getVariantBySize($size);
        if ($variant->stok <= 0) {
            return redirect()->back()
                ->withErrors(['size' => "Ukuran {$size} tidak tersedia untuk produk ini."]);
        }
        
        $user = Auth::user();
        
        $cart = Cart::firstOrCreate(
            ['id_user' => $user->id_user]
        );
        
        $existingItem = CartItem::where('id_cart', $cart->id_cart)
                                ->where('id_variant', $variant->id_variant)
                                ->first();
                                
        if ($existingItem) {
            $existingItem->qty += $quantity;
            $existingItem->save();
            $cartItemId = $existingItem->id_cart_item;
        } else {
            $newItem = CartItem::create([
                'id_cart'    => $cart->id_cart,
                'id_variant' => $variant->id_variant,
                'qty'        => $quantity,
            ]);
            $cartItemId = $newItem->id_cart_item;
        }
        
        return redirect()->route('checkout.index', ['items' => [$cartItemId]]);
    }

    public function remove($cartItemId): RedirectResponse
    {
        $user = Auth::user();
        
        $cartItem = CartItem::where('id_cart_item', $cartItemId)
            ->whereHas('cart', function($query) use ($user) {
                $query->where('id_user', $user->id_user);
            })->firstOrFail();

        $cartItem->delete();

        return redirect()->back()->with('success', 'Barang berhasil dihapus dari keranjang.');
    }
}
