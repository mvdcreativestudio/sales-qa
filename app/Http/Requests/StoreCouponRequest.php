<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCouponRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:fixed,percentage',
            'amount' => 'required|numeric',
            'due_date' => 'nullable|date',
        ];
    }
}
