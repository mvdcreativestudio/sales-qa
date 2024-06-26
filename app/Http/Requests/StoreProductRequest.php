<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:simple,configurable',
            'max_flavors' => 'nullable|integer|min:1',
            'old_price' => 'required|numeric',
            'price' => 'nullable|numeric|lt:old_price',  
            'discount' => 'nullable|numeric',
            'store_id' => 'required|exists:stores,id',
            'status' => 'required|boolean',
            'stock' => 'nullable|integer',
            'categories' => 'required|array',
            'categories.*' => 'exists:product_categories,id',
            'flavors' => 'nullable|array',
            'flavors.*' => 'exists:flavors,id',
            'image' => 'required|image|max:2048',
      ];
    }

  public function messages()
  {
      return [
               'price.lt' => 'El precio rebajado no puede ser mayor o igual al precio normal.',
              'recipes' => 'nullable|array',
              'categories' => 'Faltó completar el campo "CATEGORÍA"',
              'recipes.*.raw_material_id' => 'required_with:recipes|exists:raw_materials,id',
              'recipes.*.quantity' => 'required_with:recipes|numeric|min:0.01',
              ];
  }

}