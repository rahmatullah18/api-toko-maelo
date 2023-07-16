<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable = ["size", "stock", "type_product_id"];

    public function typeProduct()
    {
        return $this->belongsTo(TypeProduct::class);
    }
}
