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
use App\Repositories\ProductRepository;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;


class ProductController extends Controller
{

  protected $productRepo;

  public function __construct(ProductRepository $productRepo)
  {
      $this->productRepo = $productRepo;
  }

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

  public function store(StoreProductRequest $request)
  {
      $product = $this->productRepo->createProduct($request);
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


  public function update(UpdateProductRequest $request, $id)
  {
    $product = $this->productRepo->updateProduct($id, $request);
    return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
  }


  public function switchStatus()
  {
    $product = $this->productRepo->switchProductStatus(request('id'));
    return response()->json(['success' => true, 'message' => 'Estado del producto actualizado correctamente.']);
  }

  public function destroy($id)
  {
    $this->productRepo->deleteProduct($id);
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
