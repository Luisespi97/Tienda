<?php

namespace App\Services;
use Illuminate\Support\Facades\Cookie;
use App\Models\Cart;

class CartService
{
    protected $cookieName;
    protected $cookieExpiration;

    public function __construct()
    {
        $this->cookieName = config('cart.cookie.name');
        $this->cookieExpiration = config('cart.cookie.expiration');
    }    

    public function getFromCookie()
    {
        $cartId = Cookie::get($this->cookieName);

        $cart = Cart::find($cartId);// se busca si hay un odentificador de ese carro

        return  $cart;
    }

    public function getFromCookieOrCreate()// verificacion de carrito, para no crear carros cada vez que se agregue un producto
    {
        

        $cart = $this->getFromCookie();

        return $cart ?? Cart::create();// en caso de que no exista el carro se crea uno nuevo sino se va a obtener el id
    }

    public function makeCookie(Cart $cart)
    {
        return Cookie::make($this->cookieName, $cart->id, $this->cookieExpiration);// duracion de la cookie
    }

    public function countProducts()
    {
        $cart = $this->getFromCookie();

        if($cart != null){
            return $cart->products->pluck('pivot.quantity')->sum();
        }
        return 0;
    }
}    

?>