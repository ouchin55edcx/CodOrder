<?php
namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ClientsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    private $rows = 0;
    private $companyId;

    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }

    public function getRowCount()
    {
        return $this->rows;
    }

    public function model(array $row)
    {
        try {
            // Increment the row count
            $this->rows++;

            // Create or update the client
            return Client::updateOrCreate(
                [
                    'email' => $row['email'],
                    'company_id' => $this->companyId, // Ensure uniqueness within the same company
                ],
                [
                    'full_name' => $row['full_name'],
                    'phone' => $row['phone'],
                    'city' => $row['city'],
                    'address' => $row['address'],
                    'company_id' => $this->companyId, // Set the company_id
                ]
            );
        } catch (\Exception $e) {
            // Log the error and skip the row
            \Log::error('Error importing client:', [
                'row' => $row,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    public function rules(): array
    {
        return [
            '*.email' => [
                'required',
                'email',
                Rule::unique('clients', 'email')->where('company_id', $this->companyId) // Unique within the same company
            ],
            '*.full_name' => ['required', 'string'],
            '*.phone' => [
                'required',
                'string',
                Rule::unique('clients', 'phone')->where('company_id', $this->companyId) // Unique within the same company
            ],
            '*.city' => ['required', 'string'],
            '*.address' => ['required', 'string'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Email format is invalid',
            'email.unique' => 'This email is already registered in this company',
            'full_name.required' => 'Full name is required',
            'phone.required' => 'Phone number is required',
            'phone.unique' => 'This phone number is already registered in this company',
            'city.required' => 'City is required',
            'address.required' => 'Address is required',
        ];
    }
}
