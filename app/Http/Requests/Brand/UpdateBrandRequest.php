<?php

namespace App\Http\Requests\Brand;

use App\Http\Requests\BaseFormRequest;
use App\Models\Brand;
use Illuminate\Validation\Rule;
use App\Exceptions\ResourceNotFoundException;

class UpdateBrandRequest extends BaseFormRequest
{
    public function authorize()
    {
        return Brand::where('id', $this->route('id'))->exists();
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

    public function messages()
    {
        return [
            'name.unique' => 'This brand name already exists',
            'status.in' => 'Status must be either active or inactive'
        ];
    }

    protected function failedAuthorization()
    {
        throw new ResourceNotFoundException('Brand with that id does not exist');
    }
}
