<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class OrderAPIRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_value' => 'required|numeric|regex:/^\d*(\.\d{2})?$/',
            'customer_name' => 'required|string|max:255',
            'order_discount' => 'required|numeric|regex:/^\d*(\.\d{2})?$/',
        ];
    }
  
}
