<?php

namespace App\Http\Requests\Brand;

use App\Http\Requests\BaseFormRequest;

class StoreBrandRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
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
            'status.in' => 'Status must be either active or inactive'
        ];
    }
}
