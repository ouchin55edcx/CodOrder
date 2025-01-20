<?php

namespace App\Http\Requests\Brand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Brand;

class UpdateBrandRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('brands')->ignore($this->route('id'))
            ],
            'status' => 'sometimes|in:active,inactive'
        ];
    }

    protected function prepareForValidation()
    {
        if (!Brand::find($this->route('id'))) {
            throw new \Illuminate\Validation\ValidationException(
                validator([], []),
                response()->json([
                    'error' => 'Brand with that id does not exist'
                ], 404)
            );
        }
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, response()->json([
            'error' => $validator->errors()->first()
        ], 422));  // Changed to 422 for validation errors
    }
}
