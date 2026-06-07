<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_order';

    protected $fillable = [
        'id_user',
        'tanggal_order',
        'alamat_pengiriman',
        'kurir',
        'total_harga',
        'status_pesanan',
    ];

    protected $casts = [
        'tanggal_order' => 'datetime',
        'total_harga'   => 'decimal:2',
    ];

    /** Relasi: Order dimiliki satu user */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /** Relasi: Order punya banyak item */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'id_order', 'id_order');
    }

    /** Relasi: Order punya satu payment */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'id_order', 'id_order');
    }

    /** Helper: Cek apakah order sudah dibayar */
    public function isPaid(): bool
    {
        return $this->payment?->status_pembayaran === 'lunas';
    }
}
