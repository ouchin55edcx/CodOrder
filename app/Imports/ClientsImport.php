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
                    'nom_et_prenom' => $row['nom_et_prenom'],
                    'telephone' => $row['telephone'],
                    'wilaya' => $row['wilaya'],
                    'commune' => $row['commune'],
                    'adresse' => $row['adresse'],
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
            '*.nom_et_prenom' => ['required', 'string'],
            '*.telephone' => ['required', 'string'],
            '*.wilaya' => ['required', 'string'],
            '*.commune' => ['required', 'string'],
            '*.adresse' => ['required', 'string'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Email format is invalid',
            'nom_et_prenom.required' => 'Name is required',
            'telephone.required' => 'Phone number is required',
            'wilaya.required' => 'Wilaya is required',
            'commune.required' => 'Commune is required',
            'adresse.required' => 'Address is required',
        ];
    }
}