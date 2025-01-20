<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BrandController;

//
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// clients management
Route::post('clients/import', [ClientController::class, 'import']);

Route::prefix('clients')->group(function () {
    Route::get('', [ClientController::class, 'index']);
    Route::post('', [ClientController::class, 'store']);
    Route::get('/{id}', [ClientController::class, 'show']);
    Route::put('/{id}', [ClientController::class, 'update']);
    Route::delete('/{id}', [ClientController::class, 'destroy']);
});

// suppliers management
Route::prefix('suppliers')->group(function () {
    Route::get('', [SupplierController::class, 'index']);
    Route::post('', [SupplierController::class, 'store']);
    Route::get('/{id}', [SupplierController::class, 'show']);
    Route::put('/{id}', [SupplierController::class, 'update']);
    Route::delete('/{id}', [SupplierController::class, 'destroy']);
});

// brands management
Route::prefix('brands')->name('brands.')->group(function () {
    Route::get('/', [BrandController::class, 'index'])->name('index');
    Route::post('/', [BrandController::class, 'store'])->name('store');
    Route::get('/{id}', [BrandController::class, 'show'])->name('show');
    Route::put('/{id}', [BrandController::class, 'update'])->name('update');
    Route::delete('/{id}', [BrandController::class, 'destroy'])->name('destroy');
});
