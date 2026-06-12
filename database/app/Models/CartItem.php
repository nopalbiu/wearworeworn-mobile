<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_cart_item';

    protected $fillable = [
        'id_cart',
        'id_variant',
        'qty',
    ];

    protected $casts = [
        'qty' => 'integer',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'id_cart', 'id_cart');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'id_variant', 'id_variant');
    }

    public function getSubtotal(): float
    {
        return $this->qty * (float) $this->variant->product->harga;
    }
}
