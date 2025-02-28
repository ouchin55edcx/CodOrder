<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Validation\Rule;

class ClientsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;
    private $rows = 0;

    public function getRowCount()
    {
        return $this->rows;
    }
    public function model(array $row)
    {
        try {
            return Client::updateOrCreate(
                ['email' => $row['email']],
                [
                    'full_name' => $row['full_name'],
                    'phone' => $row['phone'],
                    'city' => $row['city'],
                    'address' => $row['address'],
                    'admin_id' => $row['admin_id'] ?? auth()->user()->admin->id, // Assuming admin is logged in
                ]
            );
        } catch (\Exception $e) {
            return null;
        }
    }

    public function rules(): array
    {
        return [
            '*.email' => ['required', 'email'],
            '*.full_name' => ['required', 'string'],
            '*.phone' => ['required', 'string'],
            '*.city' => ['required', 'string'],
            '*.address' => ['required', 'string'],
            '*.admin_id' => ['sometimes', 'exists:admins,id'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Email format is invalid',
            'full_name.required' => 'Full name is required',
            'phone.required' => 'Phone number is required',
            'city.required' => 'City is required',
            'address.required' => 'Address is required',
            'admin_id.exists' => 'Selected admin does not exist',
        ];
    }
}
