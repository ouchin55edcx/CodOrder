<?php
namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        return Brand::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        return Brand::create($request->all());
    }

    public function show($id)
    {
        return Brand::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:active,inactive',
        ]);

        $brand->update($request->all());

        return $brand;
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return response()->noContent();
    }
}