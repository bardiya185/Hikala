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
use 

//Users
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auths
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/who-am-i', [AuthController::class, 'whoAmI']);
});
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/check-otp', [AuthController::class, 'checkOtp']);
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

// ================================================================
// PRODUCTS (CRUD)
// ================================================================
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/featured', [ProductController::class, 'featured']);
Route::get('/products/latest', [ProductController::class, 'latest']);
Route::get('/products/category/{categoryId}', [ProductController::class, 'getByCategory']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
});

// ================================================================
// PRODUCT FILTER & SEARCH (فیلتر و جستجو - جدا از CRUD)
// ================================================================
Route::get('/products/filter-options', [ProductFilterController::class, 'options']);
Route::get('/products/filter', [ProductFilterController::class, 'filter']);
Route::get('/products/search', [ProductFilterController::class, 'search']);
Route::get('/products/advanced-search', [ProductFilterController::class, 'advancedSearch']);

// ================================================================
// PRODUCT IMAGES
// ================================================================
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/products/{product}/images', [ProductImageController::class, 'index']);
    Route::post('/products/{product}/images', [ProductImageController::class, 'store']);
    Route::delete('/product-images/{image}', [ProductImageController::class, 'destroy']);
    Route::put('/product-images/{image}/main', [ProductImageController::class, 'setMain']);
    Route::put('/product-images/reorder', [ProductImageController::class, 'reorder']);
});

// ================================================================
// CATEGORIES
// ================================================================
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/all', [CategoryController::class, 'all']);
Route::get('/categories/menu', [CategoryController::class, 'menu']); // ✅ درست
Route::get('/categories/with-products', [CategoryController::class, 'withProducts']);
Route::get('/categories/{category}/products', [CategoryController::class, 'products']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
});

// ================================================================
// BRANDS
// ================================================================
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('brands', BrandController::class);
});

// ================================================================
// ATTRIBUTES
// ================================================================
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('attributes', AttributeController::class);
});

// ================================================================
// ROLES
// ================================================================
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('roles', RoleController::class);
});

// ================================================================
// TEST
// ================================================================
Route::get('/test', [AuthController::class, 'test']);