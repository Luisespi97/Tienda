<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Image;
use App\Models\Order;
use App\Models\Scopes\AvailableScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';// manera de proteger los productos asi las clases como panel product puedan ir a esa tabala

    protected $with = [
        'images',
    ];// relaciones para nuestro modelo, en este caso las imagenes

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'price',
        'stock',
        'status',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new AvailableScope);

        static::updated(function($product) {// recibe una funcion anonima que envia el producto que se actulizo
            if ($product->stock == 0 && $product->status == 'available') {// esto se hace para verificar si el estatus del producto es diferente a disponible o no disponible
                $product->status = 'unavailable';

                $product->save();
            }
        });
    }

    public function carts()
    {
        return $this->morphedByMany(Cart::class, 'productable')->withPivot('quantity');
    }

    public function orders()
    {
        return $this->morphedByMany(Order::class, 'productable')->withPivot('quantity');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function scopeAvailable($query)
    {
        $query->where('status' , 'available');
    }

    public function getTotalAttribute()// creacion de los accesores get y mutadores set 
    {
        return $this->pivot->quantity * $this->price;
    }

}
