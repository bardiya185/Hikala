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
use App\Http\Controllers\Api\ProductFilterController;

// ================================================================
// USERS & AUTHENTICATION
// ================================================================
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/check-otp', [AuthController::class, 'checkOtp']);
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/who-am-i', [AuthController::class, 'whoAmI']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// ================================================================
// PRODUCT FILTER & SEARCH
// ================================================================
Route::prefix('products')->group(function () {
    Route::get('/filter-options', [ProductFilterController::class, 'options']);
    Route::get('/filter', [ProductFilterController::class, 'filter']);
    Route::get('/search', [ProductFilterController::class, 'search']);
    Route::get('/advanced-search', [ProductFilterController::class, 'advancedSearch']);
    Route::get('/infinite', [ProductFilterController::class, 'infinite']);
});

// ================================================================
// PRODUCTS (CRUD)
// ================================================================
// روت‌های استاتیک و خاص 
Route::get('/products/featured', [ProductController::class, 'featured']);
Route::get('/products/latest', [ProductController::class, 'latest']);
Route::get('/products/category/{categoryId}', [ProductController::class, 'getByCategory']);

// روت‌های استاندارد نمایشی
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
});

// ================================================================
// PRODUCT IMAGES 
// ================================================================
Route::get('/products/{product}/images', [ProductImageController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products/{product}/images', [ProductImageController::class, 'store']);
    Route::delete('/products/{product}/images/{image}', [ProductImageController::class, 'destroy'])->scopeBindings();
    Route::put('/products/{product}/images/{image}/main', [ProductImageController::class, 'setMain'])->scopeBindings();
    Route::put('/products/{product}/images/reorder', [ProductImageController::class, 'reorder']); // اضافه شدن {product} به مسیر برای امنیت بیشتر
});

// ================================================================
// CATEGORIES
// ================================================================
Route::get('/categories/all', [CategoryController::class, 'all']);
Route::get('/categories/menu', [CategoryController::class, 'menu']);
Route::get('/categories/with-products', [CategoryController::class, 'withProducts']);
Route::get('/categories/{category}/products', [CategoryController::class, 'products']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
});

// ================================================================
// BRANDS & ATTRIBUTES 
// ================================================================
Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brands/{brand}', [BrandController::class, 'show']);

Route::get('/attributes', [AttributeController::class, 'index']);
Route::get('/attributes/{attribute}', [AttributeController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('brands', BrandController::class)->except(['index', 'show']);
    Route::apiResource('attributes', AttributeController::class)->except(['index', 'show']);
});

// ================================================================
// ROLES & TEST
// ================================================================
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('roles', RoleController::class);
});

Route::get('/test', [AuthController::class, 'test']);