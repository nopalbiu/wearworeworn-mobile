<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_order_item';

    protected $fillable = [
        'id_order',
        'id_variant',
        'qty',
        'harga_satuan',
    ];

    protected $casts = [
        'qty'          => 'integer',
        'harga_satuan' => 'decimal:2',
    ];

    /** Relasi: Item dimiliki satu order */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    /** Relasi: Item merujuk ke satu varian produk */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'id_variant', 'id_variant');
    }

    /** Helper: Subtotal item (qty × harga_satuan) */
    public function getSubtotal(): float
    {
        return $this->qty * (float) $this->harga_satuan;
    }
}
