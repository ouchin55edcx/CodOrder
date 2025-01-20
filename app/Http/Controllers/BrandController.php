<?php

namespace App\Http\Controllers;

use App\Http\Responses\Brand\BrandResponse;
use App\Models\Brand;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use Illuminate\Support\Facades\Log;

class BrandController extends Controller
{
    public function index()
    {
        return BrandResponse::collection(Brand::paginate(10));
    }

    public function store(StoreBrandRequest $request)
    {
        $brand = Brand::create($request->validated());
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
        return response()->json(null, 204);
    }
}
