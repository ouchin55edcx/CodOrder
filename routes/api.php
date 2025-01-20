<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BrandController;


Route::apiResource('clients', ClientController::class);
Route::post('clients/import', [ClientController::class, 'import']);
Route::apiResource('suppliers', SupplierController::class);

// brands management
Route::prefix('brands')->name('brands.')->group(function () {
    Route::get('/', [BrandController::class, 'index'])->name('index');
    Route::post('/', [BrandController::class, 'store'])->name('store');
    Route::get('/{brand}', [BrandController::class, 'show'])->name('show');
    Route::put('/{id}', [BrandController::class, 'update'])->name('update');
    Route::delete('/{brand}', [BrandController::class, 'destroy'])->name('destroy');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
