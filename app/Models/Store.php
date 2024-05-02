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

    /**
     * Los atributos que pueden asignarse en masa.
     *
     * @var array
     */
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Definir la relación con Order
    public function orders()
    {
        return $this->hasMany(Order::class, 'store_id');  // Asegúrate de que 'store_id' sea la clave foránea correcta en la tabla de órdenes
    }

    /**
     * Obtiene los numeros de telefono asociados a la tienda.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
    */
    public function phoneNumber(): HasOne
    {
        return $this->hasOne(PhoneNumber::class);
    }

    public function mercadoPagoAccount()
    {
        return $this->hasOne(MercadoPagoAccount::class);
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function storeHours()
    {
        return $this->hasMany(StoreHours::class);
    }
}
