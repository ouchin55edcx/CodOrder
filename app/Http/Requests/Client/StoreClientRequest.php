<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\BaseFormRequest;


class StoreClientRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'admin_id' => 'required|exists:admins,id'
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Full name is required',
            'phone.required' => 'Phone number is required',
            //'phone.unique' => 'This phone number is already registered',
            'email.required' => 'Email address is required',
            'email.email' => 'Please provide a valid email address',
           // 'email.unique' => 'This email address is already registered',
            'city.required' => 'City is required',
            'address.required' => 'Address is required',
            'admin_id.required' => 'Admin is required',
            'admin_id.exists' => 'Selected admin does not exist'
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('full_name')) {
            $this->merge([
                'full_name' => trim($this->full_name)
            ]);
        }

        if ($this->has('phone')) {
            $this->merge([
                'phone' => trim($this->phone)
            ]);
        }

        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower(trim($this->email))
            ]);
        }

        if ($this->has('city')) {
            $this->merge([
                'city' => trim($this->city)
            ]);
        }

        if ($this->has('address')) {
            $this->merge([
                'address' => trim($this->address)
            ]);
        }
    }
}
