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
            'state' => 'sometimes|string|max:100',
            'city' => 'sometimes|string|max:100',
            'address' => 'sometimes|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'phone.unique' => 'This phone number is already registered',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'This email address is already registered'
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
        
        if ($this->has('state')) {
            $updates['state'] = trim($this->state);
        }
        
        if ($this->has('city')) {
            $updates['city'] = trim($this->city);
        }
        
        if ($this->has('address')) {
            $updates['address'] = trim($this->address);
        }
        
        if (!empty($updates)) {
            $this->merge($updates);
        }
    }
}
