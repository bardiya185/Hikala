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

/*
|--------------------------------------------------------------------------
| 🌐 PUBLIC ROUTES (بدون احراز هویت)
|--------------------------------------------------------------------------
*/

// ===== Authentication =====
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/check-otp', [AuthController::class, 'checkOtp']);
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

// ===== Products =====
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/products/{product}/images', [ProductImageController::class, 'index']);

// ===== Categories =====
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/all', [CategoryController::class, 'all']);
Route::get('/categories/menu', [CategoryController::class, 'menu']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

// ===== Brands =====
Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brands/{brand}', [BrandController::class, 'show']);

// ===== Attributes =====
Route::get('/attributes', [AttributeController::class, 'index']);
Route::get('/attributes/{attribute}', [AttributeController::class, 'show']);

// ===== Locations =====
Route::get('/provinces', [ProvinceController::class, 'index']);
Route::get('/cities', [CityController::class, 'index']);

// ===== Shipping Features (public read) =====
Route::get('variants/{variant}/shipping-features', [ProductVariantShippingFeatureController::class, 'index']);
Route::get('shipping-features/{feature}', [ProductVariantShippingFeatureController::class, 'show']);

// ===== Discounts (public read) =====
Route::get('/discounts', [DiscountController::class, 'index']);
Route::get('/discounts/{discount}', [DiscountController::class, 'show']);

// ===== Banners =====
Route::prefix('banners')->group(function () {
    Route::get('/', [BannerController::class, 'all']);
    Route::get('/position/{key}', [BannerController::class, 'byPosition']);
    Route::post('/{banner}/click', [BannerController::class, 'trackClick']);
});

// ===== Coupon Validation =====
Route::post('/coupons/validate', [CouponController::class, 'validate']);


/*
|--------------------------------------------------------------------------
| 🛒 CART ROUTES (Optional Auth - مهمان + کاربر)
|--------------------------------------------------------------------------
| با token: به عنوان کاربر
| با X-Session-Id header: به عنوان مهمان
*/

Route::prefix('cart')->middleware('optional.auth')->group(function () {
    
    Route::get('/', [CartController::class, 'index']);
    Route::delete('/', [CartController::class, 'clear']);

    Route::post('/items', [CartController::class, 'addItem']);
    Route::put('/items/{item}', [CartController::class, 'updateItem']);
    Route::delete('/items/{item}', [CartController::class, 'removeItem']);

    Route::post('/coupon', [CartController::class, 'applyCoupon']);
    Route::delete('/coupon', [CartController::class, 'removeCoupon']);
});


/*
|--------------------------------------------------------------------------
| 🔒 PROTECTED ROUTES (فقط کاربران لاگین‌کرده)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    
    // ===== User =====
    Route::get('/user', fn(Request $request) => $request->user());
    Route::get('/who-am-i', [AuthController::class, 'whoAmI']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // ===== Products Management =====
    Route::apiResource('products', ProductController::class)
        ->except(['index', 'show']);

    // ===== Product Images =====
    Route::post('/products/{product}/images', [ProductImageController::class, 'store']);
    Route::delete('/products/{product}/images/{image}', [ProductImageController::class, 'destroy'])->scopeBindings();
    Route::put('/products/{product}/images/{image}/main', [ProductImageController::class, 'setMain'])->scopeBindings();
    Route::put('/products/{product}/images/reorder', [ProductImageController::class, 'reorder'])->scopeBindings();

    // ===== Categories Management =====
    Route::apiResource('categories', CategoryController::class)
        ->except(['index', 'show']);

    // ===== Brands Management =====
    Route::apiResource('brands', BrandController::class)
        ->except(['index', 'show']);

    // ===== Attributes Management =====
    Route::apiResource('attributes', AttributeController::class)
        ->except(['index', 'show']);

    // ===== Addresses =====
    Route::apiResource('addresses', AddressController::class);

    // ===== Roles =====
    Route::apiResource('roles', RoleController::class);

    // ===== Discounts Management =====
    Route::post('/discounts', [DiscountController::class, 'store']);
    Route::put('/discounts/{discount}', [DiscountController::class, 'update']);
    Route::delete('/discounts/{discount}', [DiscountController::class, 'destroy']);

    // ===== Shipping Features Management =====
    Route::post('variants/{variant}/shipping-features', [ProductVariantShippingFeatureController::class, 'store']);
    Route::put('shipping-features/{feature}', [ProductVariantShippingFeatureController::class, 'update']);
    Route::delete('shipping-features/{feature}', [ProductVariantShippingFeatureController::class, 'destroy']);

    // ===== Cart Merge (فقط برای کاربران لاگین‌کرده) =====
    Route::post('cart/merge', [CartController::class, 'mergeCart']);

    /*
    |--------------------------------------------------------------------------
    | 🛡️ ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    
    Route::prefix('admin')->group(function () {
        
        // ===== Banners =====
        Route::get('banners', [BannerController::class, 'adminIndex']);
        Route::post('banners', [BannerController::class, 'store']);
        Route::get('banners/{banner}', [BannerController::class, 'show']);
        Route::post('banners/{banner}', [BannerController::class, 'update']);
        Route::delete('banners/{banner}', [BannerController::class, 'destroy']);
        
        // ===== Banner Positions =====
        Route::apiResource('banner-positions', BannerPositionController::class);
        
        // ===== Coupons =====
        Route::prefix('coupons')->group(function () {
            Route::get('/', [CouponController::class, 'index']);
            Route::post('/', [CouponController::class, 'store']);
            Route::get('/{coupon}', [CouponController::class, 'show']);
            Route::put('/{coupon}', [CouponController::class, 'update']);
            Route::delete('/{coupon}', [CouponController::class, 'destroy']);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | 📦 ORDER ROUTES
    |--------------------------------------------------------------------------
    */
    
    Route::prefix('orders')->group(function () {
        
                // ⏰ Delivery Options (لیست زمان‌بندی‌های تحویل)Route::get('/delivery-options', [OrderController::class, 'deliveryOptions']);

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
    });
});