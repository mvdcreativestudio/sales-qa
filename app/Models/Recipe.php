<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
  use HasFactory;

  protected $fillable = [
    'product_id',
    'flavor_id',
    'raw_material_id',
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

  /**
   * Relación con la materia prima.
   *
   * @return BelongsTo
  */
  public function rawMaterial(): BelongsTo
  {
    return $this->belongsTo(RawMaterial::class);
  }

  /**
   * Relación con el sabor.
   *
   * @return BelongsTo
  */
  public function flavor(): BelongsTo
  {
    return $this->belongsTo(Flavor::class);
  }
}
