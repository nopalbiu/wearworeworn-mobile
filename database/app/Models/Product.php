<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_product';

    protected $fillable = [
        'nama_product',
        'deskripsi',
        'material_pakaian',
        'url_size_chart',
        'harga',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'product_category',
            'id_product',
            'id_category'
        );
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'id_product', 'id_product');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'id_product', 'id_product');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class, 'id_product', 'id_product')
                    ->where('is_primary', 1);
    }

    public function getVariantBySize(string $size): ?ProductVariant
    {
        return $this->variants()
                    ->whereHas('size', fn($q) => $q->where('nama_size', $size))
                    ->first();
    }
}
