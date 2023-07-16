<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeProduct extends Model
{
    use HasFactory;

    protected $fillable = ["url", "color", "product_id"];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sizes()
    {
        return $this->hasMany(Size::class);
    }
}
