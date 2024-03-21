<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'date', 'status', 'client_id', 'currency', 'subtotal', 'tax', 'total', 'due_date'];

}
