<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use Illuminate\Support\Facades\Log;

class BrandController extends Controller
{
    public function index()
    {
        return Brand::all();
    }

    public function store(StoreBrandRequest $request)
    {
        Log::info("***store brand***");
        $brand = Brand::create($request->validated());
        return response()->json($brand, 201);
    }

    public function show(Brand $brand)
    {
        return response()->json($brand);
    }

    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $brand->update($request->validated());
        return response()->json($brand);
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return response()->json(null, 204);
    }
}
