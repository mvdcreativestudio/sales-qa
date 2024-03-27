<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Yajra\DataTables\DataTables;

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
        $category->image_url = $request->image_url;
        $category->parent_id = $request->parent_id;
        $category->status = $request->status;
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
