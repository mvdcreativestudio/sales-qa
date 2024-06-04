<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'type',
        'max_flavors',
        'old_price',
        'price',
        'discount',
        'categories',
        'tags',
        'atributtes',
        'variations',
        'image',
        'store_id',
        'status',
        'stock'
      ];


    /**
     * Obtiene la tienda a la que pertenece el producto.
     *
     * @return BelongsTo
    */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
      * Obtiene las categorías asociadas al producto.
      *
      * @return BelongsToMany
    */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class, 'category_product', 'product_id', 'category_id');
    }

    /**
      * Obtiene las ordenes de compra asociadas al producto.
      *
      * @return BelongsToMany
    */
    public function orders(): BelongsToMany
    {
    return $this->belongsToMany(Order::class, 'order_products')
                ->withPivot('quantity', 'price')
                ->withTimestamps();
    }

    /**
      * Obtiene los sabores asociados al producto.
      *
      * @return BelongsToMany
    */
    public function flavors(): BelongsToMany
    {
        return $this->belongsToMany(Flavor::class, 'product_flavor')->withTimestamps();
    }

    /**
     * Obtiene las recetas asociadas al producto.
     *
     * @return HasMany
     */
    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    /**
     * Obtiene las elaboraciones asociadas al producto.
     *
     * @return HasMany
    */
    public function productions(): HasMany
    {
        return $this->hasMany(Production::class);
    }
}
