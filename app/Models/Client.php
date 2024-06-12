<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'lastname', 'type', 'rut', 'ci', 'passport', 'doc_ext', 'address', 'city', 'state', 'country', 'phone', 'email', 'website', 'logo'];


    /**
     * Obtiene las ordenes del cliente.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Obtiene el conteo de ordenes del cliente.
     *
     * @return int
     */
    public function ordersCount(): int
    {
        return $this->orders()->count();
    }

}
