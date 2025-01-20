<?php

namespace App\Http\Controllers;

use App\Http\Responses\Supplier\SupplierResponse;
use App\Models\Supplier;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;

class SupplierController extends Controller
{
    public function index()
    {
        return SupplierResponse::collection(Supplier::paginate(10));
    }

    public function store(StoreSupplierRequest $request)
    {
        $supplier = Supplier::create($request->validated());
        return new SupplierResponse($supplier);
    }

    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return new SupplierResponse($supplier);
    }

    public function update(UpdateSupplierRequest $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->validated());
        return new SupplierResponse($supplier);
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return response()->json(null, 204);
    }
}