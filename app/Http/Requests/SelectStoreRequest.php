<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SelectStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'storeId' => 'required|integer|exists:stores,id'
        ];
    }

    public function messages()
    {
        return [
            'storeId.required' => 'El identificador de la tienda es obligatorio.',
            'storeId.integer' => 'El identificador de la tienda debe ser un número entero.',
            'storeId.exists' => 'La tienda seleccionada no existe.'
        ];
    }
}
