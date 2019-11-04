<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Uuid;

class Customer extends Model
{
    use Uuid;

    public $incrementing = false;
    
    protected $keyType = "string";
    
    protected $fillable = [
        "id",
        "name",
        "email",
        "phone1",
        "phone2",
        "status"
    ];
}
