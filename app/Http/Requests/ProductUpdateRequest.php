<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $product_id = $this->route('product')->id;

        return [
            'id_produk' => 'required|string|size:3|unique:products,id_produk,' . $product_id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'barcode' => 'required|string|max:50|unique:products,barcode,' . $product_id,
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|boolean',
        ];
    }
}
