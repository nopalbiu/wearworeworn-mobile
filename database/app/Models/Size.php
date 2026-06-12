<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Size extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_size';

    protected $fillable = [
        'nama_size',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'id_size', 'id_size');
    }
}
