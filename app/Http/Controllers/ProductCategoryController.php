<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Repositories\ProductCategoryRepository;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;

class ProductCategoryController extends Controller
{
    /**
     * El repositorio para las operaciones de categorías de productos.
     *
     * @var ProductCategoryRepository
    */
    protected $productCategoryRepo;

    /**
     * Inyecta el repositorio en el controlador.
     *
     * @param  ProductCategoryRepository  $productCategoryRepo
    */
    public function __construct(ProductCategoryRepository $productCategoryRepo)
    {
      $this->middleware(['check_permission:access_product-categories'])->only(
          [
              'index',
              'create',
              'store',
              'edit',
              'update',
              'destroy',
              'datatable'
          ]
      );

      $this->productCategoryRepo = $productCategoryRepo;
    }
    /**
     * Muestra una lista de todas las categorías de productos.
     *
     * @return View
    */
    public function index(): View
    {
      return view('content.e-commerce.backoffice.product-categories.product-categories');
    }

    /**
     * Muestra el formulario para crear una nueva categoría de producto.
     *
     * @return View
    */
    public function create(): View
    {
      return view('content.e-commerce.backoffice.product-categories.add-category');
    }

    /**
     * Almacena una nueva categoría de producto en la base de datos.
     *
     * @param StoreProductCategoryRequest $request
     * @return RedirectResponse
    */
    public function store(StoreProductCategoryRequest $request): RedirectResponse
    {
      $this->productCategoryRepo->store($request);
      return redirect()->route('product-categories.index')->with('success', 'Categoría creada correctamente.');
    }

    /**
     * Muestra el formulario para editar una categoría de producto.
     *
     * @param ProductCategory $category
     * @return View
    */
    public function edit(ProductCategory $category): View
    {
      return view('content.e-commerce.backoffice.product-categories.edit-category', compact('category'));
    }

    /**
     * Actualiza una categoría de producto en la base de datos.
     *
     * @param UpdateProductCategoryRequest $request
     * @param ProductCategory $category
     * @return RedirectResponse
    */
    public function update(UpdateProductCategoryRequest $request, ProductCategory $category): RedirectResponse
    {
      $this->productCategoryRepo->update($request, $category);
      return redirect()->route('product-categories.index')->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Elimina una categoría de producto de la base de datos.
     *
     * @param ProductCategory $category
     * @return RedirectResponse
    */
    public function destroy(ProductCategory $category): RedirectResponse
    {
      $this->productCategoryRepo->destroy($category);
      return redirect()->route('product-categories.index')->with('success', 'Categoría eliminada correctamente.');
    }

    /**
     * Obtiene los datos de las categorías de productos para DataTables.
     *
     * @return mixed
    */
    public function datatable(): mixed
    {
      return $this->productCategoryRepo->datatable();
    }
}
