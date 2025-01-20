<?php

namespace App\Http\Requests\Supplier;

use App\Http\Requests\BaseFormRequest;

class StoreSupplierRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:suppliers,name',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Supplier name is required',
            'name.unique' => 'This supplier name already exists',
            'status.required' => 'Supplier status is required',
            'status.in' => 'Status must be either active or inactive'
        ];
    }
}
