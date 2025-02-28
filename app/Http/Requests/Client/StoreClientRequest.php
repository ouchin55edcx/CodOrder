<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'full_name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('clients')->where(function ($query) {
                    // Check if the client is linked to the specified company
                    return $query->whereExists(function ($subQuery) {
                        $subQuery->from('client_company')
                            ->whereColumn('client_company.client_id', 'clients.id')
                            ->where('client_company.company_id', $this->company_id);
                    });
                })
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('clients')->where(function ($query) {
                    // Check if the client is linked to the specified company
                    return $query->whereExists(function ($subQuery) {
                        $subQuery->from('client_company')
                            ->whereColumn('client_company.client_id', 'clients.id')
                            ->where('client_company.company_id', $this->company_id);
                    });
                })
            ],
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id'
        ];
    }


    public function messages()
    {
        return [
            'full_name.required' => 'Full name is required',
            'phone.required' => 'Phone number is required',
            'phone.unique' => 'This phone number is already registered in this company',
            'email.required' => 'Email address is required',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'This email address is already registered in this company',
            'city.required' => 'City is required',
            'address.required' => 'Address is required',
            'company_id.required' => 'Company is required',
            'company_id.exists' => 'Selected company does not exist'
        ];
    }
}
