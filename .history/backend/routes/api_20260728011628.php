<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AttributeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\BannerPositionController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductImageController;
use App\Http\Controllers\Api\ProductVariantShippingFeatureController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|==================================================================================
| 🌐 PUBLIC ROUTES (No Authentication)
|==================================================================================
*/

// ===== 🔐 Authentication =====
Route::prefix('auth')->group(function () {
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/check-otp', [AuthController::class, 'checkOtp']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
});


// ===== 📦 Products (Read) =====
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{product}', [ProductController::class, 'show']);
    Route::get('/{product}/images', [ProductImageController::class, 'index']);
});


// ===== 🗂️ Categories (Read) =====
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/all', [CategoryController::class, 'all']);
    Route::get('/menu', [CategoryController::class, 'menu']);
    Route::get('/{category}', [CategoryController::class, 'show']);
});


// ===== 🏷️ Brands (Read) =====
Route::prefix('brands')->group(function () {
    Route::get('/', [BrandController::class, 'index']);
    Route::get('/{brand}', [BrandController::class, 'show']);
});


// ===== 🎨 Attributes (Read) =====
Route::prefix('attributes')->group(function () {
    Route::get('/', [AttributeController::class, 'index']);
    Route::get('/{attribute}', [AttributeController::class, 'show']);
});


// ===== 🌍 Locations =====
Route::get('/provinces', [ProvinceController::class, 'index']);
Route::get('/cities', [CityController::class, 'index']);


// ===== 🚚 Shipping Features (Read) =====
Route::get('variants/{variant}/shipping-features', [ProductVariantShippingFeatureController::class, 'index']);
Route::get('shipping-features/{feature}', [ProductVariantShippingFeatureController::class, 'show']);


// ===== 🎁 Discounts (Read) =====
Route::get('/discounts', [DiscountController::class, 'index']);
Route::get('/discounts/{discount}', [DiscountController::class, 'show']);


// ===== 🖼️ Banners (Read) =====
Route::prefix('banners')->group(function () {
    Route::get('/', [BannerController::class, 'all']);
    Route::get('/position/{key}', [BannerController::class, 'byPosition']);
    Route::post('/{banner}/click', [BannerController::class, 'trackClick']);
});


// ===== 🎫 Coupon Validation =====
Route::post('/coupons/validate', [CouponController::class, 'validate']);


// ===== ⏰ Delivery Options (Public - Frontend needs it) =====
Route::get('/delivery/options', [OrderController::class, 'deliveryOptions']);


/*
|==================================================================================
| 🛒 CART ROUTES (Optional Auth - Guest + User)
|==================================================================================
|  token: کاربر لاگین‌کرده با
|  X-Session-Id header: کاربر مهمان با
*/

Route::prefix('cart')->middleware('optional.auth')->group(function () {
    
    // Cart
    Route::get('/', [CartController::class, 'index']);
    Route::delete('/', [CartController::class, 'clear']);

    // Cart Items
    Route::post('/items', [CartController::class, 'addItem']);
    Route::put('/items/{item}', [CartController::class, 'updateItem']);
    Route::delete('/items/{item}', [CartController::class, 'removeItem']);

    // Coupon
    Route::post('/coupon', [CartController::class, 'applyCoupon']);
    Route::delete('/coupon', [CartController::class, 'removeCoupon']);
});


/*
|==================================================================================
| 🔒 AUTHENTICATED USER ROUTES
|==================================================================================
*/

Route::middleware('auth:sanctum')->group(function () {
    
    // ===== 👤 User Info =====
    Route::prefix('auth')->group(function () {
        Route::get('/me', fn(Request $request) => $request->user());
        Route::get('/who-am-i', [AuthController::class, 'whoAmI']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
    
    
    // ===== 📍 Addresses =====
    Route::apiResource('addresses', AddressController::class);
    
    
    // ===== 🛒 Cart Merge (بعد از لاگین) =====
    Route::post('/cart/merge', [CartController::class, 'mergeCart']);
    
    
    // ===== 📦 Orders =====
    Route::prefix('orders')->group(function () {
        // Checkout
        Route::post('/checkout', [OrderController::class, 'checkout']);
        
        // List & Detail
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{order}', [OrderController::class, 'show']);
        
        // Actions
        Route::post('/{order}/cancel', [OrderController::class, 'cancel']);
        Route::post('/{order}/refund', [OrderController::class, 'refund']);
        Route::post('/{order}/pay', [OrderController::class, 'pay']);
    });
});


/*
|==================================================================================
| 🛡️ ADMIN ROUTES (Only Admin Users)
|==================================================================================
*/

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    
    // ===== 📦 Products Management =====
    Route::apiResource('products', ProductController::class)
        ->except(['index', 'show']);
    
    // Product Images
    Route::prefix('products/{product}/images')->scopeBindings()->group(function () {
        Route::post('/', [ProductImageController::class, 'store']);
        Route::delete('/{image}', [ProductImageController::class, 'destroy']);
        Route::put('/{image}/main', [ProductImageController::class, 'setMain']);
        Route::put('/reorder', [ProductImageController::class, 'reorder']);
    });
    
    
    // ===== 🗂️ Categories Management =====
    Route::apiResource('categories', CategoryController::class)
        ->except(['index', 'show']);
    
    
    // ===== 🏷️ Brands Management =====
    Route::apiResource('brands', BrandController::class)
        ->except(['index', 'show']);
    
    
    // ===== 🎨 Attributes Management =====
    Route::apiResource('attributes', AttributeController::class)
        ->except(['index', 'show']);
    
    
    // ===== 🎁 Discounts Management =====
    Route::prefix('discounts')->group(function () {
        Route::post('/', [DiscountController::class, 'store']);
        Route::put('/{discount}', [DiscountController::class, 'update']);
        Route::delete('/{discount}', [DiscountController::class, 'destroy']);
    });
    
    
    // ===== 🎫 Coupons Management =====
    Route::apiResource('coupons', CouponController::class);
    
    
    // ===== 🚚 Shipping Features Management =====
    Route::post('variants/{variant}/shipping-features', [ProductVariantShippingFeatureController::class, 'store']);
    Route::put('shipping-features/{feature}', [ProductVariantShippingFeatureController::class, 'update']);
    Route::delete('shipping-features/{feature}', [ProductVariantShippingFeatureController::class, 'destroy']);
    
    
    // ===== 🖼️ Banners Management =====
    Route::prefix('banners')->group(function () {
        Route::get('/', [BannerController::class, 'adminIndex']);
        Route::post('/', [BannerController::class, 'store']);
        Route::get('/{banner}', [BannerController::class, 'show']);
        Route::post('/{banner}', [BannerController::class, 'update']); // POST + _method=PUT for file upload
        Route::delete('/{banner}', [BannerController::class, 'destroy']);
    });
    
    // Banner Positions
    Route::apiResource('banner-positions', BannerPositionController::class);
    
    
    // ===== 👥 Roles Management =====
    Route::apiResource('roles', RoleController::class);
});