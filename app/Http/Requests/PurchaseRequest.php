<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider_id' => ['required', 'integer', 'exists:providers,id'],
            'storage_id' => ['required', 'integer', 'exists:storages,id'],
            'purchased_at' => ['required', 'date'],

            'products' => ['required', 'array', 'min:1'],

            'products.*.product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'products.*.qty' => [
                'required',
                'integer',
                'min:1',
            ],

            'products.*.purchase_price' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }
}
