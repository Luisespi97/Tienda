<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Services\CartService;
use Illuminate\Validation\ValidationException;

class ProductCartController extends Controller
{
    public $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        $cart = $this->cartService->getFromCookieOrCreate();

        $quantity = $cart->products()
            ->find($product->id)
            ->pivot
            ->quantity ?? 0;// agregando elementos al carro

            if($product->stock < $quantity+1){
                throw ValidationException::withMessages([
                    'product' => "There is not enough stock for the quantity you required of {$product->title}",
                ]);
            }

        $cart->products()->syncWithoutDetaching([
            $product->id => ['quantity' => $quantity + 1],
        ]);

        $cart->touch();

        $cookie = $this->cartService->makeCookie($cart);


        return redirect()->back()->cookie($cookie);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Cart $cart)
    {
        $cart->products()->detach($product->id);

        $cart->touch();

        $cookie =$this->cartService->makeCookie($cart);
        
        return redirect()->back();
    }

    /*public function getFromCookieOrCreate()// verificacion de carrito, para no crear carros cada vez que se agregue un producto
    {
        $cartId = Cookie::get('cart');

        $cart = Cart::find($cartId);// se busca si hay un odentificador de ese carro

        return $cart ?? Cart::create();// en caso de que no exista el carro se crea uno nuevo sino se va a obtener el id
    }*/
}
