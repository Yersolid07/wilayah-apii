<?php

use App\Http\Controllers\Api\V1\ProvinceController;
use App\Http\Controllers\Api\V1\RegencyController;
use App\Http\Controllers\Api\V1\DistrictController;
use App\Http\Controllers\Api\V1\VillageController;
use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// API V1 routes (public)
Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('auth/login', [AuthController::class, 'login']);
    
    // Protected routes
    Route::middleware('auth:api')->group(function () {
        // Provinces
        Route::get('/provinces', [ProvinceController::class, 'index']);
        Route::get('/provinces/{id}', [ProvinceController::class, 'show']);
        Route::post('/provinces', [ProvinceController::class, 'store']);
        Route::patch('/provinces/{id}', [ProvinceController::class, 'update']);
        Route::delete('/provinces/{id}', [ProvinceController::class, 'destroy']);
        Route::get('/provinces/{province_id}/regencies', [RegencyController::class, 'getByProvince']);

        // Regencies
        Route::get('/regencies', [RegencyController::class, 'index']);
        Route::get('/regencies/{id}', [RegencyController::class, 'show']);
        Route::post('/regencies', [RegencyController::class, 'store']);
        Route::patch('/regencies/{id}', [RegencyController::class, 'update']);
        Route::delete('/regencies/{id}', [RegencyController::class, 'destroy']);
        Route::get('/regencies/{regency_id}/districts', [DistrictController::class, 'getByRegency']);

        // Districts
        Route::get('/districts/{district_id}/villages', [VillageController::class, 'getByDistrict']);
    });
});

// Protected routes (if needed)
Route::middleware('auth:sanctum')->group(function () {
    // Add protected routes here
});