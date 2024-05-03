<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;

class ProductCategoryController extends Controller
{
    public function index()
    {
        return view('content.e-commerce.backoffice.product-categories.product-categories');
    }

    public function create()
    {
        return view('content.e-commerce.backoffice.product-categories.add-category');
    }

    public function store(Request $request) {
      $category = new ProductCategory();
      $category->name = $request->name;
      $category->slug = $request->slug;
      $category->description = $request->description;
      $category->parent_id = $request->parent_id;
      $category->status = $request->status;

      if ($request->hasFile('image')) {
          $file = $request->file('image');
          Log::debug('File info:', ['name' => $file->getClientOriginalName(), 'size' => $file->getSize(), 'mime_type' => $file->getMimeType(), 'path' => $file->getRealPath()]);

          // Obtener el nombre original del archivo
          $filename = time() . '.' . $file->getClientOriginalExtension();

          // Mover el archivo a la nueva ubicación
          $path = $file->move(public_path('assets/img/ecommerce-images'), $filename);

          // Guardar la ruta en la base de datos
          $category->image_url = 'assets/img/ecommerce-images/' . $filename;
      } else {
          Log::debug('No image file found in the request');
      }

      $category->save();

      return redirect()->route('product-categories.index')->with('success', 'Categoría creada correctamente.');
    }


    public function edit(ProductCategory $category)
    {
        return view('content.e-commerce.backoffice.product-categories.edit-category', compact('category'));
    }

    public function update(Request $request, ProductCategory $category)
    {
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->description = $request->description;
        $category->image_url = $request->image_url;
        $category->parent_id = $request->parent_id;
        $category->status = $request->status;
        $category->save();
        return redirect()->route('product-categories.index')->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(ProductCategory $category)
    {
        $category->delete();
        return redirect()->route('product-categories.index')->with('success', 'Categoría eliminada correctamente.');
    }

    public function datatable()
    {
        $query = ProductCategory::select(['id', 'name', 'slug', 'description', 'image_url', 'parent_id', 'status']);
        return DataTables::of($query)
            ->make(true);
    }
}
