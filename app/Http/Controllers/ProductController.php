<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use App\Models\ProductCategory;
use Yajra\DataTables\DataTables;


class ProductController extends Controller
{

  public function index()
  {
      return view('content.e-commerce.backoffice.products.products');
  }

  public function create()
  {
    $categories = ProductCategory::all();
    $stores = Store::all();
    return view('content.e-commerce.backoffice.products.add-product', compact('stores', 'categories'));
  }

  public function store(Request $request)
  {
    $product = new Product();
    $product->name = $request->name;
    $product->sku = $request->sku;
    $product->description = $request->description;
    $product->type = $request->type;
    $product->old_price = $request->old_price;
    $product->price = $request->price;
    $product->discount = $request->discount;
    $product->tags = $request->tags;
    $product->atributtes = $request->atributtes;
    $product->variations = $request->variations;
    $product->image = $request->image;
    $product->store_id = $request->store_id;
    $product->status = $request->status;
    $product->stock = $request->stock;

    // Verificar si se guardará como borrador
    if ($request->action === 'save_draft') {
      $product->draft = 1;
    } else {
      $product->draft = 0;
    }

    $product->save();

    // Sincroniza las categorías después de guardar el producto
    $product->categories()->sync($request->input('categories', []));

    // Redireccionar al usuario a la lista de clientes con un mensaje de éxito
    return redirect()->route('products.index')->with('success', 'Producto creado correctamente.');
  }

  public function datatable()
  {
      $query = Product::select(['id', 'name', 'sku', 'description', 'type', 'old_price', 'price', 'discount', 'tags', 'atributtes', 'variations', 'image', 'store_id', 'status', 'stock', 'draft']);
      return DataTables::of($query)
          ->make(true);
  }
}
