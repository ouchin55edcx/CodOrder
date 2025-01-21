<?php

namespace App\Http\Requests\Supplier;

use App\Http\Requests\BaseFormRequest;
use App\Models\Supplier;
use Illuminate\Validation\Rule;
use App\Exceptions\ResourceNotFoundException;

class UpdateSupplierRequest extends BaseFormRequest
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
                Rule::unique('suppliers')->ignore($this->route('id'))
            ],
            'status' => 'sometimes|in:active,inactive'
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'This supplier name already exists',
            'status.in' => 'Status must be either active or inactive'
        ];
    }

    protected function prepareForValidation()
    {
        $supplier = Supplier::find($this->route('id'));
        
        if (!$supplier) {
            throw new ResourceNotFoundException('Supplier with that id does not exist');
        }
    }
}
