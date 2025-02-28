<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function show()
    {
        // Get the authenticated admin's company
        $company = Auth::user()->admin->company;

        return response()->json($company);
    }

    public function update(Request $request)
    {
        // Get the authenticated admin's company
        $company = Auth::user()->admin->company;

        // Validate the request
        $validatedData = $request->validate([
            'company_name' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'shop_name' => 'sometimes|string|max:255',
            'website' => 'nullable|url|max:255',
            'how_you_heard' => 'sometimes|string|max:255',
            'ecommerce_progress' => 'sometimes|string|max:255',
            'order_management_tool' => 'sometimes|string|max:255',
            'organization_size' => 'sometimes|string|max:255',
            'business_model' => 'sometimes|string|max:255',
        ]);

        // Update the company
        $company->update($validatedData);

        return response()->json($company);
    }
}
