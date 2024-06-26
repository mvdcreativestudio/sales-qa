<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ramsey\Uuid\Uuid;


class Order extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'time', 'origin', 'client_id', 'store_id', 'products', 'subtotal', 'tax', 'shipping', 'coupon_id', 'coupon_amount', 'discount', 'total', 'payment_status', 'shipping_status', 'payment_method', 'shipping_method', 'estimate_id', 'shipping_id', 'uuid'];

    /**
     * The "booted" method of the model.
     *
     * Genera un UUID cuando se crea una nueva orden.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Generar UUID cuando se crea una orden
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Uuid::uuid4()->toString();
            }
        });
    }

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

    /**
     * Obtiene el nombre de la clave de ruta para el modelo.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Obtiene los cambios de estado de la orden.
     *
     * @return HasMany
    */
    public function statusChanges()
    {
      return $this->hasMany(OrderStatusChange::class);
    }



}
