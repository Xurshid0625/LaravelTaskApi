<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRefundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => [
                'required',
                'integer',
                'exists:orders,id',
            ],

            'products' => [
                'required',
                'array',
                'min:1',
            ],

            'products.*.order_product_id' => [
                'required',
                'integer',
                'exists:order_products,id',
            ],

            'products.*.qty' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }
}
