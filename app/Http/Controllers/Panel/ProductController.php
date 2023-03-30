<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\PanelProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;



class ProductController extends Controller
{
    /**public function __construct() se deja de usar debido a la implementacion del panel de usuarios
    {
        $this->middleware('auth');
    }*/

    public function index()
    {
       
        return view('products.index')->with([
            'products' => PanelProduct::without('images')->get(),
        ]);
    }
    public function create()
    {
        return view('products.create');
    }
    public function store(ProductRequest $request)
    {

        //dd($request->validated());
        $product = PanelProduct::create($request->validated());

        foreach ($request->images as $image) {//  creacion de almacenamiento de imagenes
            $product->images()->create([
                'path' => 'images/' . $image->store('products', 'images')
            ]);
        }
        return redirect()
            ->route('products.index')
            ->withSuccess("The new product with id {$product->id} was created");
            //with(['success' => "The new product with id {$product->id} was created"])
        
    }
    public function show(PanelProduct $product)
    {

        return view('products.show')->with([
            'product' => $product,
        ]);
    }
    public function edit(PanelProduct $product)
    {
        return view('products.edit')->with([
            'product' => ($product),
        ]);
    }
    public function update(PanelProduct $product, ProductRequest $request)
    {
        //dd($request->validated()); Pruebas para chequear el funcionamiento
        
        $product->update($request->validated());// para ingresar los valores editados en la lista de productos

        if($request->hasFile('images')){
            foreach($product->images as $image){// borrando las imagenes del producto, las anteriores
                $path = storage_path("app/public/{$image->path}");

                File::delete($path);// ruta de imagen a remover
                $image->delete();

            }

            foreach ($request->images as $image) {//  creacion de almacenamiento de imagenes
                $product->images()->create([
                    'path' => 'images/' . $image->store('products', 'images')
                ]);
            }
        }

        return redirect()
            ->route('products.index')
            ->withSuccess("The product with id {$product->id} was edited");
    }
    public function destroy(PanelProduct $product)
    {
        //$product = ($product);

        $product->delete();
        return redirect()
            ->route('products.index')
            ->withSuccess("The product with id {$product->id} was deleted");
    }
}
