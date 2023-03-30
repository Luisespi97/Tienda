<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class PanelProduct extends Product
{
    use HasFactory;

    protected static function booted()
    {
        // static::addGlobalScope(new AvailableScope);

        // static::updated(function($product) {
        //     if ($product->stock == 0 && $product->status == 'available') {
        //         $product->status = 'unavailable';

        //         $product->save();
        //     }
        // });
    }
    public function getForeignKey()
    {
        $parent = get_parent_class($this);// creacion de un metodo para usar a la clase padre

        return (new $parent)->getForeignKey();
    }
    public function getMorphClass()
    {
        $parent = get_parent_class($this);// creacion de un metodo para usar a la clase padre

        return (new $parent)->getMorphClass();
    }
}
