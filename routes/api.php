<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WilayahController;
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

// Authentication routes
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
});

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::get('/provinces', [WilayahController::class, 'getProvinces']);
    Route::get('/cities', [WilayahController::class, 'getCitiesByProvince']); // Endpoint untuk kota
    Route::get('/districts', [WilayahController::class, 'getDistrictsByCity']); // Endpoint untuk kecamatan
    Route::get('/subdistricts', [WilayahController::class, 'getSubdistrictsByDistrict']); // Endpoint untuk kelurahan
});