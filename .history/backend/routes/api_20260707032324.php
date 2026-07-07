<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttributeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductImageController;

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

<<<<<<< HEAD
// ========== تغییرات اینجا شروع میشه ==========

=======
>>>>>>> 736f8abadf05856da72d399896cd6c1db491537e
// Products (فقط GET‌ها عمومی، بقیه نیاز به auth دارن)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
});

// Images (همه نیاز به auth دارن)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/products/{product}/images', [ProductImageController::class, 'index']);
    Route::post('/products/{product}/images', [ProductImageController::class, 'store']);
    Route::delete('/product-images/{image}', [ProductImageController::class, 'destroy']);
    Route::put('/product-images/{image}/main', [ProductImageController::class, 'setMain']);
    Route::put('/product-images/reorder', [ProductImageController::class, 'reorder']);
});

// Categories (فقط GET‌ها عمومی، بقیه نیاز به auth دارن)
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
});

// Brands (همه نیاز به auth دارن)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('brands', BrandController::class);
});

// Attributes (همه نیاز به auth دارن)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('attributes', AttributeController::class);
});

// Roles (همه نیاز به auth دارن)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('roles', RoleController::class);
});

<<<<<<< HEAD
// ========== تغییرات اینجا تموم میشه ==========

//Test
Route::get('/test', [AuthController::class, 'test']);
=======

//Test
Route::get('/test', [AuthController::class, 'test']);
>>>>>>> 736f8abadf05856da72d399896cd6c1db491537e
