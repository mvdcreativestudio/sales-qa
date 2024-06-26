<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFlavorRequest;
use App\Repositories\ProductRepository;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\SwitchProductStatusRequest;
use App\Http\Requests\StoreMultipleFlavorsRequest;
use App\Http\Requests\UpdateFlavorRequest;

class ProductController extends Controller
{
  /**
   * El repositorio para las operaciones de productos.
   *
   * @var ProductRepository
  */
  protected $productRepo;

  /**
   * Inyecta el repositorio en el controlador.
   *
   * @param  ProductRepository  $productRepo
  */
  public function __construct(ProductRepository $productRepo)
  {
    $this->middleware(['check_permission:access_products', 'user_has_store'])->only(
        [
            'index',
            'create',
            'store',
            'edit',
            'update',
            'destroy',
            'switchStatus',
            'flavors',
            'storeFlavor',
            'storeMultipleFlavors',
            'editFlavor',
            'updateFlavor',
            'destroyFlavor',
            'switchFlavorStatus'
        ]
    );

    $this->productRepo = $productRepo;
  }

  /**
   * Muestra una lista de todos los productos.
   *
   * @return View
  */
  public function index(): View
  {
      return view('content.e-commerce.backoffice.products.products');
  }

  /**
   * Muestra el formulario para crear un nuevo producto.
   *
   * @return View
  */
  public function create(): View
  {
    $product = $this->productRepo->create();
    return view('content.e-commerce.backoffice.products.add-product', $product);
  }

  /**
   * Almacena un nuevo producto en la base de datos.
   *
   * @param StoreProductRequest $request
   * @return RedirectResponse
  */
  public function store(StoreProductRequest $request): RedirectResponse
  {
    $validated = $request->validated();

    $this->productRepo->createProduct($request);

    return redirect()->route('products.index')->with('success', 'Producto creado correctamente.');
  }

  /**
   * Obtiene los datos de los productos para DataTables.
   *
   * @return mixed
  */
  public function datatable(): mixed
  {
    return $this->productRepo->getProductsForDataTable();
  }

  /**
   * Muestra el formulario para editar un producto.
   *
   * @param  int  $id
   * @return View
  */
  public function edit(int $id): View
  {
    $product = $this->productRepo->edit($id);
    return view('content.e-commerce.backoffice.products.edit-product', $product) ;
  }

  /**
   * Actualiza un producto específico en la base de datos.
   *
   * @param UpdateProductRequest $request
   * @param int $id
   * @return RedirectResponse
  */
  public function update(UpdateProductRequest $request, $id)
  {
    $this->productRepo->update($id, $request);
    return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
  }

  /**
    * Cambia el estado de un producto.
    *
    * @param SwitchProductStatusRequest $request
    * @return JsonResponse
  */
  public function switchStatus(SwitchProductStatusRequest $request): JsonResponse
  {
    $this->productRepo->switchProductStatus($request->id);
    return response()->json(['success' => true, 'message' => 'Estado del producto actualizado correctamente.']);
  }

  /**
   * Elimina un producto de la base de datos.
   *
   * @param int $id
   * @return JsonResponse
  */
  public function destroy(int $id): JsonResponse
  {
    $this->productRepo->delete($id);
    return response()->json(['success' => true, 'message' => 'Producto eliminado correctamente.']);
  }

  /**
   * Muestra una lista de todos los sabores.
   *
   * @return View
  */
  public function flavors(): View
  {
    $flavors = $this->productRepo->flavors();
    return view('content.e-commerce.backoffice.products.flavors', $flavors);
  }

  /**
   * Obtiene los datos de los sabores para DataTables.
   *
   * @return mixed
  */
  public function flavorsDatatable(): mixed
  {
    return $this->productRepo->flavorsDatatable();
  }

  /**
   * Almacena un sabor
   *
   * @param StoreFlavorRequest $request
   * @return RedirectResponse
  */
  public function storeFlavor(StoreFlavorRequest $request): RedirectResponse
  {
    $this->productRepo->storeFlavor($request);
    return redirect()->route('product-flavors')->with('success', 'Sabor creado correctamente.');
  }

  /**
   * Almacena múltiples sabores
   *
   * @param StoreMultipleFlavorsRequest $request
   * @return JsonResponse
  */
  public function storeMultipleFlavors(StoreMultipleFlavorsRequest $request): JsonResponse
  {
    $this->productRepo->storeMultipleFlavors($request);
    return response()->json(['success' => true, 'message' => 'Sabores múltiples creados correctamente.']);
  }

  /**
   * Muestra el formulario para editar un sabor.
   *
   * @param  int  $id
   * @return View
  */
  public function editFlavor(int $id): JsonResponse
  {
    $flavor = $this->productRepo->editFlavor($id);
    return response()->json($flavor);
  }

  /**
   * Actualiza un sabor específico en la base de datos.
   *
   * @param  UpdateFlavorRequest  $request A CAMBIAR JEJEJE
   * @param  int  $id
   * @return View
  */
  public function updateFlavor(UpdateFlavorRequest $request, int $id): JsonResponse
  {
      $flavor = $this->productRepo->updateFlavor($request, $id);
      return response()->json(['success' => true, 'message' => 'Sabor actualizado con éxito']);
  }

  /**
   * Elimina un sabor de la base de datos.
   *
   * @param  int  $id
   * @return JsonResponse
  */
  public function destroyFlavor(int $id): JsonResponse
  {
    $this->productRepo->destroyFlavor($id);
    return response()->json(['success' => true, 'message' => 'Sabor eliminado correctamente.']);
  }

  /**
   * Cambia el estado de un sabor.
   *
   * @param  int  $id
   * @return JsonResponse
  */
  public function switchFlavorStatus(int $id): JsonResponse
  {
    $this->productRepo->switchFlavorStatus($id);
    return response()->json(['success' => true, 'message' => 'Estado del sabor actualizado correctamente.']);
  }
}
