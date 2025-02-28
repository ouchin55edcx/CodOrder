<?php
namespace App\Http\Controllers;

use App\Http\Responses\Supplier\SupplierResponse;
use App\Models\Supplier;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index()
    {
        return SupplierResponse::collection(Supplier::paginate(10));
    }

    public function store(StoreSupplierRequest $request)
    {
        $admin = Auth::user()->admin;
        
        // Check trial limits
        if ($admin->supplier_count >= 10 && $admin->user->isOnTrial()) {
            return response()->json([
                'message' => 'Trial limit reached. You can only create 10 suppliers during trial period.'
            ], 403);
        }

        $supplier = Supplier::create($request->validated());
        
        // Increment count
        $admin->increment('supplier_count');

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
        
        // Decrement count
        auth()->Auth::admin->decrement('supplier_count');

        return response()->json(null, 204);
    }
}