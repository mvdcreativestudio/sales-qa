<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class EcommerceController extends Controller
{
    public function index()
    {
        return view('content.e-commerce.front.index');
    }

    public function store()
    {
      $products = Product::all();
        return view('content.e-commerce.front.store', compact('products'));
    }




    public function marketing()
    {
        return view('content.e-commerce.backoffice.marketing');
    }

    public function settings()
    {
        return view('content.e-commerce.backoffice.settings');
    }

}
