<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductRepository
{
    public function createProduct(Request $request)
    {
        $product = new Product();
        $product->fill($request->only([
            'name', 'sku', 'description', 'type', 'max_flavors', 'old_price',
            'price', 'discount', 'store_id', 'status', 'stock'
        ]));

        Log::debug('Request data:', $request->all());

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            Log::debug('File info:', [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'path' => $file->getRealPath()
            ]);

            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->move(public_path('assets/img/ecommerce-images'), $filename);
            $product->image = 'assets/img/ecommerce-images/' . $filename;
        } else {
            Log::debug('No image file found in the request');
        }

        $product->draft = $request->action === 'save_draft' ? 1 : 0;
        $product->save();

        // Sincronizar relaciones
        $product->categories()->sync($request->input('categories', []));
        if ($request->filled('flavors')) {
            $product->flavors()->sync($request->flavors);
        }

        return $product;
    }

    public function updateProduct($id, Request $request)
    {
        $product = Product::findOrFail($id);
        $product->update($request->only([
            'name', 'sku', 'description', 'type', 'max_flavors', 'old_price',
            'price', 'discount', 'store_id', 'status', 'stock'
        ]));

        $product->categories()->sync($request->input('categories', []));
        if ($request->filled('flavors')) {
            $product->flavors()->sync($request->flavors);
        }

        return $product;
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->is_trash = 1;
        $product->save();

        return $product;
    }

    public function switchProductStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->status = $product->status == '1' ? '2' : '1';
        $product->save();

        return $product;
    }
}
