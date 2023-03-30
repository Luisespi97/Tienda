<?php

namespace App\Http\Controllers;

use App\Models\Product;

class MainController extends Controller
{
    public function index()
    {
        /*$name = config('app.undefined', 'welcome');
        dd($name);
        //dd();*/
        //\DB::connection()->enableQueryLog();// todas las consultas que se lleven acabo van a estar dentro de log
        $products = Product::all();// para realizar consultas con el scope

        return view('welcome')->with([
            'products' => $products,
        ]);
    }
}
