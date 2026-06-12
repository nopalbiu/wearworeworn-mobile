<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_cart';

    protected $fillable = [
        'id_user',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /** Relasi: Cart punya banyak item */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class, 'id_cart', 'id_cart');
    }
}
