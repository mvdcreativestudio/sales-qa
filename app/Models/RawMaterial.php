<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image_url', 'unit_of_measure', 'store_id', 'stock'];

    /**
     * Obtiene la tienda a la que pertenece la materia prima.
     *
     * @return BelongsTo
    */
    public function store(): BelongsTo
    {
      return $this->belongsTo(Store::class);
    }

    /**
     * Obtiene las ordenes de compra asociadas a la materia prima.
     *
     * @return BelongsToMany
    */
    public function supplierOrders(): BelongsToMany
    {
      return $this->belongsToMany(SupplierOrder::class, 'supplier_order_raw_material')
          ->withPivot('quantity')
          ->withTimestamps();
    }
}
