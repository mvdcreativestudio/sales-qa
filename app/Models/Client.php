<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'lastname', 'type', 'rut', 'ci', 'passport', 'doc_ext', 'address', 'city', 'state', 'country', 'phone', 'email', 'website', 'logo'];


    // Relación con Order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
