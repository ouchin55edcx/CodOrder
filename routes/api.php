<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CompanyController;
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

    // Client routes
    Route::prefix('clients')->group(function () {
        Route::get('/', [ClientController::class, 'index']);
        Route::post('/', [ClientController::class, 'store']);
        Route::get('/{id}', [ClientController::class, 'show']);
        Route::put('/{id}', [ClientController::class, 'update']);
        Route::delete('/{id}', [ClientController::class, 'destroy']);
        Route::post('/import', [ClientController::class, 'import']);
    });

    // Logout route
    Route::post('/logout', function () {
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    });

    // Suppliers
    Route::prefix('suppliers')->group(function () {
        Route::post('/', [SupplierController::class, 'store'])->middleware('can:manage-suppliers');
        Route::get('/', [SupplierController::class, 'index'])->middleware('can:manage-suppliers');
        Route::get('/{id}', [SupplierController::class, 'show'])->middleware('can:manage-suppliers');
        Route::put('/{id}', [SupplierController::class, 'update'])->middleware('can:manage-suppliers');
        Route::delete('/{id}', [SupplierController::class, 'destroy'])->middleware('can:manage-suppliers');
    });

    // Brands
    Route::prefix('brands')->group(function () {
        Route::post('/', [BrandController::class, 'store'])->middleware('can:manage-brands');
        Route::get('/', [BrandController::class, 'index'])->middleware('can:manage-brands');
        Route::get('/{id}', [BrandController::class, 'show'])->middleware('can:manage-brands');
        Route::put('/{id}', [BrandController::class, 'update'])->middleware('can:manage-brands');
        Route::delete('/{id}', [BrandController::class, 'destroy'])->middleware('can:manage-brands');
    });

    // Company routes
    Route::get('/company', [CompanyController::class, 'show']);
    Route::put('/company', [CompanyController::class, 'update']);
});
