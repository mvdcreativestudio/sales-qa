<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Production extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
    ];

    /**
     * Relación con el producto.
     *
     * @return BelongsTo
    */
    public function product(): BelongsTo
    {
      return $this->belongsTo(Product::class);
    }
}
