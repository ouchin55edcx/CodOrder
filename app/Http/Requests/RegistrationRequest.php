<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'agree_to_terms' => 'required|boolean',
            'company_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'shop_name' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'how_you_heard' => 'required|string|max:255',
            'ecommerce_progress' => 'required|string|max:255',
            'order_management_tool' => 'required|string|max:255',
            'organization_size' => 'required|string|max:255',
            'business_model' => 'required|string|max:255',
        ];
    }
}
