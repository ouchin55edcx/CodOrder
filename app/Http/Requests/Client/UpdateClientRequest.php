<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
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
                Rule::unique('clients')->ignore($this->route('id'))->where(function ($query) {
                    return $query->whereExists(function ($subQuery) {
                        $subQuery->from('client_company')
                            ->whereColumn('client_company.client_id', 'clients.id')
                            ->where('client_company.company_id', $this->company_id);
                    });
                })
            ],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('clients')->ignore($this->route('id'))->where(function ($query) {
                    return $query->whereExists(function ($subQuery) {
                        $subQuery->from('client_company')
                            ->whereColumn('client_company.client_id', 'clients.id')
                            ->where('client_company.company_id', $this->company_id);
                    });
                })
            ],
            'city' => 'sometimes|string|max:100',
            'address' => 'sometimes|string|max:255',
            'company_id' => 'sometimes|exists:companies,id'
        ];
    }


    public function messages()
    {
        return [
            'phone.unique' => 'This phone number is already registered in this company',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'This email address is already registered in this company',
            'company_id.exists' => 'Selected company does not exist'
        ];
    }
}
