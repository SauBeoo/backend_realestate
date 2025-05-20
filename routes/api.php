<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\PropertySearchController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Property routes
Route::prefix('properties')->group(function () {
    Route::get('/', [PropertyController::class, 'index']);
    Route::get('/{id}', [PropertyController::class, 'show']);
    Route::post('/', [PropertyController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/{id}', [PropertyController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{id}', [PropertyController::class, 'destroy'])->middleware('auth:sanctum');
    
    // Additional property routes
    Route::get('/type/{type}', [PropertyController::class, 'getByType']);
    Route::get('/owner/{ownerId}', [PropertyController::class, 'getByOwner']);
});

// Property search routes
Route::prefix('property-search')->group(function () {
    Route::get('/search', [PropertySearchController::class, 'search']);
    Route::get('/nearby', [PropertySearchController::class, 'nearby']);
    Route::get('/similar/{id}', [PropertySearchController::class, 'similar']);
});
