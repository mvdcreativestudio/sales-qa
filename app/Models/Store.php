<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Store extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'email',
        'rut',
        'status',
        'slug',
        'closed',
    ];

    /**
     * Obtiene los usuarios asociados a la tienda.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Obtiene los productos asociados a la tienda.
     *
     * @return HasMany
    */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'store_id');  // Asegúrate de que 'store_id' sea la clave foránea correcta en la tabla de órdenes
    }

    /**
     * Obtiene los numeros de telefono asociados a la tienda.
     *
     * @return HasOne
    */
    public function phoneNumber(): HasOne
    {
        return $this->hasOne(PhoneNumber::class);
    }

    /**
     * Obtiene la cuenta de Mercado Pago asociada a la tienda.
     *
     * @return HasOne
    */
    public function mercadoPagoAccount(): HasOne
    {
        return $this->hasOne(MercadoPagoAccount::class);
    }

    /**
     * Obtiene las horas de apertura y cierre de la tienda.
     *
     * @return SlugOptions
    */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Obtiene las horas de apertura y cierre de la tienda.
     *
     * @return HasMany
     */
    public function storeHours(): HasMany
    {
        return $this->hasMany(StoreHours::class);
    }
}
