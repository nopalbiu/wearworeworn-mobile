<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_payment';

    protected $fillable = [
        'id_order',
        'metode_pembayaran',
        'status_pembayaran',
        'jumlah_bayar',
        'waktu_transaksi',
        'bukti_pembayaran',
    ];

    protected $casts = [
        'jumlah_bayar'    => 'decimal:2',
        'waktu_transaksi' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    public function isLunas(): bool
    {
        return $this->status_pembayaran === 'lunas';
    }
}
