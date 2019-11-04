<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Uuid;

class Product extends Model
{
    use Uuid;

    public $incrementing = false;
    
    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'qtd_available',
        'qtd_total',
        'customer_id'
    ];
}
