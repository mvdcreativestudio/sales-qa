<?php

namespace App\Repositories;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use Yajra\DataTables\DataTables;
use App\Models\ProductCategory;
use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Flavor;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\StoreFlavorRequest;
use App\Http\Requests\StoreMultipleFlavorsRequest;
use App\Http\Requests\UpdateFlavorRequest;
use Illuminate\Support\Facades\Auth;



class ProductRepository
{
  /**
   * Muestra el formulario para crear un nuevo producto.
   *
   * @return array
  */
  public function create(): array
  {
    $categories = ProductCategory::all();
    $stores = Store::all();
    $flavors = Flavor::all();

    return compact('stores', 'categories', 'flavors');
  }

  /**
   * Almacena un nuevo producto en base de datos.
   *
   * @param  StoreProductRequest  $request
   * @return Product
  */
  public function createProduct(StoreProductRequest $request): Product
  {
    $product = new Product(); // Se crea una nueva instancia del modelo Product.
    $product->fill($request->only([
        'name', 'sku', 'description', 'type', 'max_flavors', 'old_price',
        'price', 'discount', 'store_id', 'status', 'stock'
    ])); // Se rellenan los campos del producto con los datos del formulario.

    if ($request->hasFile('image')) { // Si se ha subido un archivo de imagen.
        $file = $request->file('image');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $path = $file->move(public_path('assets/img/ecommerce-images'), $filename);
        $product->image = 'assets/img/ecommerce-images/' . $filename;
    }

    $product->draft = $request->action === 'save_draft' ? 1 : 0; // Se establece el estado de borrador del producto.
    $product->save(); // Se guarda el producto en la base de datos.

    $product->categories()->sync($request->input('categories', [])); // Se sincronizan las categorías del producto.
    if ($request->filled('flavors')) { // Si se han seleccionado sabores.
        $product->flavors()->sync($request->flavors); // Se sincronizan los sabores del producto.
    }

    return $product;
  }

  /**
   * Obtiene los datos de los productos para DataTables.
   *
   * @return mixed
  */
  public function getProductsForDataTable(): mixed
  {
    $query = Product::with(['categories:id,name', 'store:id,name'])
        ->select(['id', 'name', 'sku', 'description', 'type', 'old_price', 'price', 'discount', 'image', 'store_id', 'status', 'stock', 'draft'])
        ->where('is_trash', '!=', 1);

    // Filtrar por rol del usuario
    if (!Auth::user()->hasRole('Administrador')) {
        $query->where('store_id', Auth::user()->store_id);
    }

    $dataTable = DataTables::of($query)
      ->addColumn('category', function ($product) {
        return $product->categories->implode('name', ', ');
      })
      ->addColumn('store_name', function ($product) {
        return $product->store->name;
      })
      ->make(true);

    return $dataTable;
  }

  /**
   * Devuelve un producto específico.
   *
   * @param  int  $id
   * @return array
  */
  public function edit(int $id): array
  {
    $product = Product::with('categories', 'flavors')->findOrFail($id);
    $categories = ProductCategory::all();
    $stores = Store::all();
    $flavors = Flavor::all();

    return compact('product', 'stores', 'categories', 'flavors');
  }

  /**
   * Actualiza un producto específico en la base de datos.
   *
   * @param  int  $id
   * @param  UpdateProductRequest  $request
   * @return Product
  */
  public function update(int $id, UpdateProductRequest $request): Product
  {
    $product = Product::findOrFail($id); // Se obtiene el producto a actualizar.
    $product->update($request->only([
        'name', 'sku', 'description', 'type', 'max_flavors', 'old_price',
        'price', 'discount', 'store_id', 'status', 'stock'
    ])); // Se actualizan los campos del producto con los datos del formulario.

    if ($request->hasFile('image')) { // Si se ha subido un archivo de imagen.
      $file = $request->file('image');
      $filename = time() . '.' . $file->getClientOriginalExtension();
      $path = $file->move(public_path('assets/img/ecommerce-images'), $filename);
      if ($path) {
          $product->image = 'assets/img/ecommerce-images/' . $filename;
          $product->save();
      }
    }

    $product->categories()->sync($request->input('categories', [])); // Se sincronizan las categorías del producto.
    if ($request->filled('flavors')) { // Si se han seleccionado sabores.
        $product->flavors()->sync($request->flavors); // Se sincronizan los sabores del producto.
    }

    return $product;
  }

  /**
   * Cambia el estado de un producto.
   *
   * @param  int  $id
   * @return Product
  */
  public function switchProductStatus(int $id): Product
  {
    $product = Product::findOrFail($id);
    $product->status = $product->status == '1' ? '2' : '1';
    $product->save();

    return $product;
  }

  /**
   * Elimina un producto de la base de datos.
   *
   * @param  int  $id
   * @return Product
  */
  public function delete(int $id): Product
  {
    $product = Product::findOrFail($id);
    $product->is_trash = 1;
    $product->save();

    return $product;
  }

  /**
   * Obtiene los sabores de los productos.
   *
   * @return Collection
  */
  public function flavors(): Collection
  {
    return Flavor::all();
  }

  /**
   * Obtiene los datos de los sabores para DataTables.
   *
   * @return mixed
  */
  public function flavorsDatatable(): mixed
  {
    $flavors = Flavor::all();

    return DataTables::of($flavors)
        ->addColumn('action', function($flavor){
            return '<a href="#" class="btn btn-primary btn-sm">Editar</a>';
        })
        ->rawColumns(['action'])
        ->make(true);
  }

  /**
   * Almacena los sabores
   *
   * @param  StoreFlavorRequest  $request
   * @return Flavor
  */
  public function storeFlavor(StoreFlavorRequest $request): Flavor
  {
    $flavor = new Flavor();
    $flavor->name = $request->name;
    $flavor->status = $request->status ?? 'active';

    $flavor->save();

    return $flavor;
  }

  /**
   * Almacena múltiples sabores
   *
   * @param  StoreMultipleFlavorsRequest  $request
   * @return void
  */
  public function storeMultipleFlavors(StoreMultipleFlavorsRequest $request): void
  {
    $data = json_decode($request->getContent(), true);
    $names = $data['name'];
    $status = $data['status'] ?? 'active';

    foreach ($names as $name) {
        $flavor = new Flavor();
        $flavor->name = trim($name);
        $flavor->status = $status;
        $flavor->save();
    }
  }

  /**
   * Muestra el formulario para editar un sabor.
   *
   * @param  int  $id
   * @return Flavor
  */
  public function editFlavor(int $id): Flavor
  {
    return Flavor::findOrFail($id);
  }

  /**
   * Actualiza un sabor específico en la base de datos.
   *
   * @param  UpdateFlavorRequest  $request
   * @param  int  $id
   * @return array
  */
  public function updateFlavor(UpdateFlavorRequest $request, int $id): array
  {
    $flavor = Flavor::findOrFail($id);
    $flavor->name = $request->name;
    $flavor->save();

    return compact('flavor');
  }

  /**
   * Cambia el estado de un sabor.
   *
   * @param  int  $id
   * @return Flavor
  */
  public function switchFlavorStatus(int $id): Flavor
  {
    $flavor = Flavor::findOrFail($id);
    $flavor->status = $flavor->status === 'active' ? 'inactive' : 'active';
    $flavor->save();

    return $flavor;
  }

  /**
   * Elimina un sabor de la base de datos.
   *
   * @param  int  $id
   * @return bool
  */
  public function destroyFlavor(int $id): bool
  {
    $flavor = Flavor::findOrFail($id);
    return $flavor->delete();
  }
}
