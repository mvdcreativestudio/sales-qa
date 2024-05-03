<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use App\Models\ProductCategory;
use App\Models\Flavor;
use App\Http\Requests\StoreFlavorRequest;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;


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
    $flavors = Flavor::all();
    return view('content.e-commerce.backoffice.products.add-product', compact('stores', 'categories', 'flavors'));
  }

  public function store(Request $request)
  {
    $product = new Product();
    $product->name = $request->name;
    $product->sku = $request->sku;
    $product->description = $request->description;
    $product->type = $request->type;
    $product->max_flavors = $request->max_flavors;
    $product->old_price = $request->old_price;
    $product->price = $request->price;
    $product->discount = $request->discount;
    $product->store_id = $request->store_id;
    $product->status = $request->status;
    $product->stock = $request->stock;

    // Agregar logs para depuración
    Log::debug('Request data:', $request->all());

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        Log::debug('File info:', ['name' => $file->getClientOriginalName(), 'size' => $file->getSize(), 'mime_type' => $file->getMimeType(), 'path' => $file->getRealPath()]);

        // Obtener el nombre original del archivo
        $filename = time() . '.' . $file->getClientOriginalExtension();

        // Mover el archivo a la nueva ubicación
        $path = $file->move(public_path('assets/img/ecommerce-images'), $filename);

        // Guardar la ruta en la base de datos
        $product->image = 'assets/img/ecommerce-images/' . $filename;
    } else {
        Log::debug('No image file found in the request');
    }

    // Verificar si se guardará como borrador
    if ($request->action === 'save_draft') {
        $product->draft = 1;
    } else {
        $product->draft = 0;
    }

    $product->save();

    // Sincroniza las categorías después de guardar el producto
    $product->categories()->sync($request->input('categories', []));

    //Manejo de sabores
    if ($request->filled('flavors')) {
    $product->flavors()->sync($request->flavors);
    }

    // Redireccionar al usuario a la lista de clientes con un mensaje de éxito
    return redirect()->route('products.index')->with('success', 'Producto creado correctamente.');
  }


  public function datatable()
  {
      $query = Product::with(['categories:id,name', 'store:id,name'])
                      ->select(['id', 'name', 'sku', 'description', 'type', 'old_price', 'price', 'discount', 'image', 'store_id', 'status', 'stock', 'draft'])
                      ->where('is_trash', '!=', 1);

      return DataTables::of($query)
          ->addColumn('category', function ($product) {
              return $product->categories->implode('name', ', ');
          })
          ->addColumn('store_name', function ($product) {
              return $product->store->name;
          })
          ->make(true);
  }


  public function edit($id)
  {
    $product = Product::with('categories', 'flavors')->findOrFail($id);
    $categories = ProductCategory::all();
    $stores = Store::all();
    $flavors = Flavor::all();

    return view('content.e-commerce.backoffice.products.edit-product', compact('product', 'stores', 'categories', 'flavors'));
  }


  public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    // Actualiza solo los campos necesarios
    $product->update([
        'name' => $request->input('name'),
        'sku' => $request->input('sku'),
        'description' => $request->input('description'),
        'type' => $request->input('type'),
        'max_flavors' => $request->input('max_flavors'),
        'old_price' => $request->input('old_price'),
        'price' => $request->input('price'),
        'discount' => $request->input('discount'),
        'store_id' => $request->input('store_id'),
        'status' => $request->input('status'),
        'stock' => $request->input('stock'),
    ]);

    // Sincroniza las categorías
    $product->categories()->sync($request->input('categories', []));

    // Sincroniza los sabores
    if ($request->filled('flavors')) {
        $product->flavors()->sync($request->input('flavors', []));
    }

    return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
}


  public function switchStatus()
  {
      $product = Product::findOrFail(request('id'));
      if ($product->status == '1') {
          $product->status = '2';
      } else {
          $product->status = '1';
      }
      $product->save();

      return response()->json(['success' => true, 'message' => 'Estado del producto actualizado correctamente.']);
  }

  public function destroy($id)
  {
      $product = Product::findOrFail($id);
      $product->is_trash = 1;
      $product->save();

      return response()->json(['success' => true, 'message' => 'Producto eliminado correctamente.']);
  }


  public function flavors()
  {
    $flavor = Flavor::all();
    return view('content.e-commerce.backoffice.products.flavors', compact('flavor'));
  }

  public function flavorsDatatable()
  {
      $flavors = Flavor::all();

      return DataTables::of($flavors)
          ->addColumn('action', function($flavor){
              return '<a href="#" class="btn btn-primary btn-sm">Editar</a>';
          })
          ->rawColumns(['action'])
          ->make(true);
  }

  public function storeFlavors(StoreFlavorRequest $request)
  {
      $flavor = new Flavor();
      $flavor->name = $request->name;
      $flavor->status = $request->status ?? 'active';

      $flavor->save();

      return redirect()->route('product-flavors')->with('success', 'Sabor creado correctamente.');
  }

  public function storeMultipleFlavors(Request $request)
  {
      $data = json_decode($request->getContent(), true);
      $names = $data['name'];
      $status = $data['status'] ?? 'active';  // Asume 'active' si no se especifica

      foreach ($names as $name) {
          $flavor = new Flavor();
          $flavor->name = trim($name);
          $flavor->status = $status;
          $flavor->save();
      }

      return response()->json(['success' => true, 'message' => 'Sabores múltiples creados correctamente.']);
  }


  public function editFlavor($id)
  {
      $flavors = Flavor::findOrFail($id);
      return view('content.e-commerce.backoffice.products.flavors.edit-flavor', compact('flavors'));
  }

  public function updateFlavor($id)
  {
      $flavor = Flavor::findOrFail($id);
      $flavor->name = request('name');
      $flavor->save();

      return view ('content.e-commerce.backoffice.products.flavors', compact('flavor'))->with('success', 'Sabor actualizado correctamente.');
  }

  public function destroyFlavor($id)
  {
      $flavor = Flavor::findOrFail($id);
      $flavor->delete();

      return response()->json(['success' => true, 'message' => 'Sabor eliminado correctamente.']);
  }

  public function switchFlavorStatus($id)
  {
      $flavor = Flavor::findOrFail($id);
      $flavor->status = $flavor->status === 'active' ? 'inactive' : 'active';
      $flavor->save();

      return response()->json(['success' => true, 'message' => 'Estado del sabor actualizado correctamente.']);
  }


}
