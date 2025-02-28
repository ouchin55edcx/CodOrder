<?php
namespace App\Http\Controllers;

use App\Http\Responses\Brand\BrandResponse;
use App\Models\Brand;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    public function index()
    {
        return BrandResponse::collection(Brand::paginate(10));
    }

    public function store(StoreBrandRequest $request)
    {
        $admin = Auth::user()->admin;
        
        // Check trial limits
        if ($admin->brand_count >= 10 && $admin->user->isOnTrial()) {
            return response()->json([
                'message' => 'Trial limit reached. You can only create 10 brands during trial period.'
            ], 403);
        }

        $brand = Brand::create($request->validated());
        
        // Increment count
        $admin->increment('brand_count');

        return new BrandResponse($brand);
    }

    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        return new BrandResponse($brand);
    }

    public function update(UpdateBrandRequest $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->update($request->validated());
        return new BrandResponse($brand);
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        
        // Decrement count
        auth()->Auth::admin->decrement('brand_count');

        return response()->json(null, 204);
    }
}