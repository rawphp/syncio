<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|numeric',
            'title' => 'required|string',
            'description' => 'string',
            'images' => 'array',
            'images.*.id' => 'required|numeric',
            'images.*.position' => 'required|numeric',
            'images.*.url' => 'required|string',
            'variants' => 'array',
            'variants.*.id' => 'required|numeric',
            'variants.*.sku' => 'required|string',
            'variants.*.barcode' => 'required|string',
            'variants.*.image_id' => 'numeric',
            'variants.*.inventory_quantity' => 'required|numeric',
        ];
    }
}
