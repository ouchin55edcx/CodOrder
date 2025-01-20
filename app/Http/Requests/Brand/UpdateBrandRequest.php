<?php

namespace App\Http\Requests\Brand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Add authorization logic if needed
    }

    public function rules()
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('brands')->ignore($this->brand)
            ],
            'status' => 'sometimes|in:active,inactive',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'This brand name already exists',
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
