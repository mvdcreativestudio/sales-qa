<?php

namespace App\Repositories;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductCategoryRepository
{
  /**
   * Almacena una nueva categoría de producto en la base de datos.
   *
   * @param  Request  $request
   * @return ProductCategory
  */
  public function store(Request $request): ProductCategory
  {
    $category = new ProductCategory();
    $category->name = $request->name;
    $category->slug = $request->slug;
    $category->description = $request->description;
    $category->parent_id = $request->parent_id;
    $category->status = $request->status;

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $path = $file->move(public_path('assets/img/ecommerce-images'), $filename);
        $category->image_url = 'assets/img/ecommerce-images/' . $filename;
    }

    $category->save();

    return $category;
  }

  /**
   * Actualiza una categoría de producto en la base de datos.
   *
   * @param  Request  $request
   * @param  ProductCategory  $category
   * @return ProductCategory
  */
  public function update(Request $request, ProductCategory $category): ProductCategory
  {
    $category->name = $request->name;
    $category->slug = $request->slug;
    $category->description = $request->description;
    $category->parent_id = $request->parent_id;
    $category->status = $request->status;

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $path = $file->move(public_path('assets/img/ecommerce-images'), $filename);
        $category->image_url = 'assets/img/ecommerce-images/' . $filename;
    }

    $category->save();

    return $category;
  }

  /**
   * Elimina una categoría de producto de la base de datos.
   *
   * @param  ProductCategory  $category
   * @return void
  */
  public function destroy(ProductCategory $category): void
  {
    $category->delete();
  }

  /**
   * Obtiene los datos de las categorías de productos para DataTables.
   *
   * @return mixed
  */
  public function datatable(): mixed
  {
    $query = ProductCategory::select(['id', 'name', 'slug', 'description', 'image_url', 'parent_id', 'status']);
    return DataTables::of($query)
            ->make(true);
  }
}
