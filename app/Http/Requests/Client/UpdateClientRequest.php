<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\BaseFormRequest;
use App\Models\Client;
use Illuminate\Validation\Rule;
use App\Exceptions\ResourceNotFoundException;
class UpdateClientRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'full_name' => 'sometimes|string|max:255',
            'phone' => [
                'sometimes',
                'string',
                'max:20',
                Rule::unique('clients', 'phone')->ignore($this->route('id'))
            ],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('clients', 'email')->ignore($this->route('id'))
            ],
            'city' => 'sometimes|string|max:100',
            'address' => 'sometimes|string|max:255',
            'admin_id' => 'sometimes|exists:admins,id'
        ];
    }

    public function messages()
    {
        return [
            'phone.unique' => 'This phone number is already registered',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'This email address is already registered',
            'admin_id.exists' => 'Selected admin does not exist'
        ];
    }

    protected function prepareForValidation()
    {
        $client = Client::findOrFail($this->route('id'));

        $updates = [];

        if ($this->has('full_name')) {
            $updates['full_name'] = trim($this->full_name);
        }

        if ($this->has('phone')) {
            $updates['phone'] = trim($this->phone);
        }

        if ($this->has('email')) {
            $updates['email'] = strtolower(trim($this->email));
        }

        if ($this->has('city')) {
            $updates['city'] = trim($this->city);
        }

        if ($this->has('address')) {
            $updates['address'] = trim($this->address);
        }

        if ($this->has('admin_id')) {
            $updates['admin_id'] = $this->admin_id;
        }

        if (!empty($updates)) {
            $this->merge($updates);
        }
    }
}
