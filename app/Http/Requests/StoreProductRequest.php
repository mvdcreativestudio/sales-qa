<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:simple,configurable',
            'max_flavors' => 'nullable|integer|min:1',
            'old_price' => 'required|numeric',
            'price' => 'required|numeric|lt:old_price',
            'discount' => 'nullable|numeric',
            'store_id' => 'required|exists:stores,id',
            'status' => 'required|boolean',
            'stock' => 'nullable|integer',
            'categories' => 'required|array',
            'categories.*' => 'exists:product_categories,id',
            'flavors' => 'nullable|array',
            'flavors.*' => 'exists:flavors,id',
            'recipes' => 'nullable|array',
            'recipes.*.raw_material_id' => 'required_without:recipes.*.used_flavor_id|exists:raw_materials,id',
            'recipes.*.used_flavor_id' => 'required_without:recipes.*.raw_material_id|exists:flavors,id',
            'recipes.*.quantity' => 'required_with:recipes|numeric|min:0.01',
        ];
    }

    public function messages()
    {
        return [
            'price.lt' => 'El precio rebajado no puede ser mayor o igual al precio normal.',
            'recipes.*.raw_material_id.required_without' => 'El campo materia prima es obligatorio cuando no se selecciona un sabor.',
            'recipes.*.used_flavor_id.required_without' => 'El campo sabor es obligatorio cuando no se selecciona una materia prima.',
            'recipes.*.quantity.required_with' => 'La cantidad es obligatoria cuando se agrega una receta.',
        ];
    }
}
