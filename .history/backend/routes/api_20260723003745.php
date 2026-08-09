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
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\ProductVariantShippingFeatureController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\BannerPositionController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\OrderController;

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

Route::prefix('cart')->group(function () {
   
    Route::get('/', [CartController::class, 'index']);
    Route::delete('/', [CartController::class, 'clear']);

    Route::post('/items', [CartController::class, 'addItem']);
    Route::put('/items/{item}', [CartController::class, 'updateItem']);
    Route::delete('/items/{item}', [CartController::class, 'removeItem']);
 
    Route::post('/coupon', [CartController::class, 'applyCoupon']);
    Route::delete('/coupon', [CartController::class, 'removeCoupon']);
    
});

    Route::post('/coupons/validate', [CouponController::class, 'validate']);
    


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

    Route::prefix('admin')->group(function () {
        Route::get('banners', [BannerController::class, 'adminIndex']);
        Route::post('banners', [BannerController::class, 'store']);
        Route::get('banners/{banner}', [BannerController::class, 'show']);
        Route::post('banners/{banner}', [BannerController::class, 'update']); // POST + _method=PUT
        Route::delete('banners/{banner}', [BannerController::class, 'destroy']);

        // Banner Positions
        Route::apiResource('banner-positions', BannerPositionController::class);
    });
    
        //cart merge route
        Route::post('cart/merge', [CartController::class, 'mergeCart']);

   
        Route::prefix('admin/coupons')->group(function () {
            Route::get('/', [CouponController::class, 'index']);
            Route::post('/', [CouponController::class, 'store']);
            Route::get('/{coupon}', [CouponController::class, 'show']);
            Route::put('/{coupon}', [CouponController::class, 'update']);
            Route::delete('/{coupon}', [CouponController::class, 'destroy']);
        });

        Route::prefix('orders')->group(function () {

          // 🛒 Checkout (تبدیل سبد به سفارش)
            Route::post('/checkout', [OrderController::class, 'checkout']);
            
            // 📋 لیست سفارش‌های کاربر
            Route::get('/', [OrderController::class, 'index']);
            
            // 🔍 جزئیات یه سفارش
            Route::get('/{order}', [OrderController::class, 'show']);
            
            // ❌ لغو سفارش
            Route::post('/{order}/cancel', [OrderController::class, 'cancel']);
            
            // 🔄 درخواست مرجوع
            Route::post('/{order}/refund', [OrderController::class, 'refund']);
            
            // 💳 پرداخت (Fake)
            Route::post('/{order}/pay', [OrderController::class, 'pay']);

        })

});

