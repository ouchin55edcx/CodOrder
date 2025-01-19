<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function index()
    {
        return response()->json(Supplier::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => ['required', Rule::in(['active', 'inactive', 'pending'])],
        ]);

        $supplier = Supplier::create($validated);
        return response()->json($supplier, 201);
    }

    public function show(Supplier $supplier)
    {
        return response()->json($supplier);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'status' => [Rule::in(['active', 'inactive', 'pending'])],
        ]);

        $supplier->update($validated);
        return response()->json($supplier);
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->json(null, 204);
    }
}