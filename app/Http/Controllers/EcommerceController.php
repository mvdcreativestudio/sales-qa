<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EcommerceController extends Controller
{
    public function index()
    {
        return view('content.e-commerce.front.index');
    }

    public function store()
    {
        return view('content.e-commerce.front.store');
    }
    
    public function checkout()
    {
        return view('content.e-commerce.front.checkout');
    }

}
