<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image_url', 'unit_of_measure', 'store_id'];

    /**
     * Obtiene la tienda a la que pertenece la materia prima.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function store()
    {
      return $this->belongsTo(\App\Models\Store::class);
    }
}
