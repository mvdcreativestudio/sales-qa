<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;
use App\Models\Flavor;
use Illuminate\Support\Facades\Log;

class EcommerceController extends Controller
{
    public function index()
    {
      $stores = Store::all();
      return view('content.e-commerce.front.index', compact('stores'));
    }

    public function store($storeId)
    {
      $store = Store::find($storeId);

      if (!$store) {
          return redirect()->route('home')->with('error', 'La tienda no existe.');
      }

      $categories = ProductCategory::whereHas('products', function ($query) use ($storeId) {
          $query->where('status', '=', 1)->where('store_id', $storeId)->where('is_trash', '!=', 1);
      })->with(['products' => function ($query) use ($storeId) {
          $query->where('status', '=', 1)->where('store_id', $storeId)->where('is_trash', '!=', 1);
      }])->get();

      // Cargar todos los sabores disponibles
      $flavors = Flavor::all();

      // Pasar tanto las categorías, los sabores como la tienda a la vista
      return view('content.e-commerce.front.store', compact('categories', 'flavors', 'store'));
    }



    public function marketing()
    {
        return view('content.e-commerce.backoffice.marketing');
    }

    public function settings()
    {
        return view('content.e-commerce.backoffice.settings');
    }

    public function success()
    {
        return view('content.e-commerce.front.success');
    }

    public function failure()
    {
        return view('content.e-commerce.front.failure');
    }

    public function pending()
    {
        return view('content.e-commerce.front.pending');
    }


}
