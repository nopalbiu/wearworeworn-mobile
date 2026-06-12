<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class ProductVariant extends Model
{
    use HasFactory;
 
    protected $primaryKey = 'id_variant';

    protected $fillable = [
        'id_product',
        'id_size',
        'stok',
    ];
 
    protected $casts = [
        'stok' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'id_size', 'id_size');
    }

    public function isInStock(): bool
    {
        return $this->stok > 0;
    }
}