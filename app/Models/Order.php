<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'time', 'origin', 'client_id', 'store_id', 'products', 'subtotal', 'tax', 'shipping', 'coupon_id', 'coupon_amount', 'discount', 'total', 'payment_status', 'shipping_status', 'payment_method', 'shipping_method', 'estimate_id', 'shipping_id'];

    /**
     * Obtiene el cliente al que pertenece el pedido.
     *
     * @return BelongsTo
    */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Obtiene los productos asociados al pedido.
     *
     * @return BelongsToMany
    */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_products')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }

    /**
     * Obtiene la tienda a la que pertenece el pedido.
     *
     * @return BelongsTo
    */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Obtiene el cupón asociado al pedido.
     *
     * @return BelongsTo
    */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

}
