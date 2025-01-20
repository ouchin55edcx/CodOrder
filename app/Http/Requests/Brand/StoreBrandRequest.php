<?php

namespace App\Http\Requests\Brand;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Add authorization logic if needed
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:brands,name',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Brand name is required',
            'name.unique' => 'This brand name already exists',
            'status.required' => 'Brand status is required',
            'status.boolean' => 'Status must be active or inactive'
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, response()->json([
            'message' => $validator->errors()->first(),
        ], 422));
    }
}
