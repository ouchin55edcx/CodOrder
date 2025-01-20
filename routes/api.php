<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BrandController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('clients', ClientController::class);
Route::post('clients/import', [ClientController::class, 'import']);
Route::apiResource('suppliers', SupplierController::class);
Route::apiResource('brands', BrandController::class);