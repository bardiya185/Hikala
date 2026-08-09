<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttributeController;
use App\Http\Controllers\Api\ProductController;
//use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\ProductImageController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\ProductVariantShippingFeatureController;
use BannerController

// ================================================================
// PUBLIC ROUTES
// ================================================================

Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/check-otp', [AuthController::class, 'checkOtp']);
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::get('/products/{product}/images', [ProductImageController::class, 'index']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/all', [CategoryController::class, 'all']);
Route::get('/categories/menu', [CategoryController::class, 'menu']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brands/{brand}', [BrandController::class, 'show']);

Route::get('/attributes', [AttributeController::class, 'index']);
Route::get('/attributes/{attribute}', [AttributeController::class, 'show']);

Route::get('/provinces', [ProvinceController::class, 'index']);

Route::get('/cities', [CityController::class, 'index']);

Route::get(
    'variants/{variant}/shipping-features',
    [ProductVariantShippingFeatureController::class, 'index']
);

Route::get(
    'shipping-features/{feature}',
    [ProductVariantShippingFeatureController::class, 'show']
);


Route::get('/discounts', [DiscountController::class, 'index']);

Route::get('/discounts/{discount}', [DiscountController::class, 'show']);

// routes/api.php

Route::prefix('banners')->group(function () {
    Route::get('/', [BannerController::class, 'all']);
    Route::get('/position/{key}', [BannerController::class, 'byPosition']);
    Route::post('/{banner}/click', [BannerController::class, 'trackClick']);
});


// ================================================================
// PROTECTED ROUTES
// ================================================================

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', fn(Request $request) => $request->user());

    Route::get('/who-am-i', [AuthController::class, 'whoAmI']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('products', ProductController::class)
        ->except(['index', 'show']);

    Route::post('/products/{product}/images', [ProductImageController::class, 'store']);
    Route::delete('/products/{product}/images/{image}', [ProductImageController::class, 'destroy'])->scopeBindings();
    Route::put('/products/{product}/images/{image}/main', [ProductImageController::class, 'setMain'])->scopeBindings();
    Route::put('/products/{product}/images/reorder', [ProductImageController::class, 'reorder'])->scopeBindings();

    Route::apiResource('categories', CategoryController::class)
        ->except(['index', 'show']);

    Route::apiResource('brands', BrandController::class)
        ->except(['index', 'show']);

    Route::apiResource('attributes', AttributeController::class)
        ->except(['index', 'show']);

    Route::apiResource('addresses', AddressController::class);

    Route::apiResource('roles', RoleController::class);

    Route::post('/discounts', [DiscountController::class, 'store']);

    Route::put('/discounts/{discount}', [DiscountController::class, 'update']);

    Route::delete('/discounts/{discount}', [DiscountController::class, 'destroy']);

    Route::post(
        'variants/{variant}/shipping-features',
        [ProductVariantShippingFeatureController::class, 'store']
    );

    Route::put(
        'shipping-features/{feature}',
        [ProductVariantShippingFeatureController::class, 'update']
    );

    Route::delete(
        'shipping-features/{feature}',
        [ProductVariantShippingFeatureController::class, 'destroy']
    );

});

