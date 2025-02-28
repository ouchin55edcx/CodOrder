<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LoginController;

// Public routes
Route::post('/register', [RegistrationController::class, 'register']);
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/verify-email/{token}', [VerificationController::class, 'verify'])->name('verification.verify');

// Protected routes with Sanctum and Admin role
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin', function (Request $request) {
        return $request->user()->load('admin');
    });

    // Clients
    Route::prefix('clients')->group(function () {
        Route::post('', [ClientController::class, 'store'])->middleware('can:manage-clients');
        Route::get('', [ClientController::class, 'index'])->middleware('can:manage-clients');
        Route::get('/{id}', [ClientController::class, 'show'])->middleware('can:manage-clients');
        Route::put('/{id}', [ClientController::class, 'update'])->middleware('can:manage-clients');
        Route::delete('/{id}', [ClientController::class, 'destroy'])->middleware('can:manage-clients');
    });

    // Suppliers
    Route::prefix('suppliers')->group(function () {
        Route::post('', [SupplierController::class, 'store'])->middleware('can:manage-suppliers');
        Route::get('', [SupplierController::class, 'index'])->middleware('can:manage-suppliers');
        Route::get('/{id}', [SupplierController::class, 'show'])->middleware('can:manage-suppliers');
        Route::put('/{id}', [SupplierController::class, 'update'])->middleware('can:manage-suppliers');
        Route::delete('/{id}', [SupplierController::class, 'destroy'])->middleware('can:manage-suppliers');
    });

    // Brands
    Route::prefix('brands')->group(function () {
        Route::post('', [BrandController::class, 'store'])->middleware('can:manage-brands');
        Route::get('', [BrandController::class, 'index'])->middleware('can:manage-brands');
        Route::get('/{id}', [BrandController::class, 'show'])->middleware('can:manage-brands');
        Route::put('/{id}', [BrandController::class, 'update'])->middleware('can:manage-brands');
        Route::delete('/{id}', [BrandController::class, 'destroy'])->middleware('can:manage-brands');
    });

    // Logout
    Route::post('/logout', function (Request $request) {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    });
});
