<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttributeController

//Users
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// َAuths
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/who-am-i', [AuthController::class, 'whoAmI']);
});
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/check-otp', [AuthController::class, 'checkOtp']);
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);


//Products
Route::middleware(['auth:sanctum'])->group(function () {

    Route::apiResource('products', ProductController::class);

});


//
Route::post(

    '/products/{product}/images',
    
    [ProductImageController::class,'store']
    
    )->middleware('auth:sanctum');


// Categories
Route::prefix('categories')->group(function () {

    Route::get('/', [CategoryController::class, 'index']);

    Route::post('/', [CategoryController::class, 'store']);

    Route::get('/{category}', [CategoryController::class, 'show']);

    Route::put('/{category}', [CategoryController::class, 'update']);

    Route::delete('/{category}', [CategoryController::class, 'destroy']);

});

// Brands
Route::apiResource('brands', BrandController::class);

//Attributes
Route::apiResource('attributes', AttributeController::class);

//Roles
Route::apiResource('roles', RoleController::class);

//Test
Route::get('/test', [AuthController::class, 'test']);
