<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        "quantity",
        "total_price",
        "status",
        "product_id",
        "user_id",
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}