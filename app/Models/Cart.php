<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->morphToMany(Product::class, 'productable')->withPivot('quantity');
    }

    public function getTotalAttribute()// creacion de los accesores get y mutadores set 
    {
        return $this->products->pluck('total')->sum();
    }
}