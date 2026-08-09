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
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DiscountCampaignController;


/*
|==================================================================================
| 🌐 PUBLIC ROUTES (No Authentication)
|==================================================================================
*/

// ===== 🔐 Authentication =====
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/check-otp', [AuthController::class, 'checkOtp']);
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);


// ===== 📦 Products (Read) =====
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{product}', [ProductController::class, 'show']);
    Route::get('/{product}/images', [ProductImageController::class, 'index']);
    Route::get('/{product}/related', [ProductController::class, 'related']); 
});


// ===== 🗂️ Categories (Read) =====
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
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


/*
|==================================================================================
| 🛒 CART ROUTES (Optional Auth - Guest + User)
|==================================================================================
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


// ===== 🎯 Discount Campaigns (Public) =====
Route::prefix('campaigns')->group(function () {
    Route::get('/', [DiscountCampaignController::class, 'index']);
    Route::get('/{slug}', [DiscountCampaignController::class, 'showBySlug']);
});

Route::prefix('products/{product}/reviews')->group(function () {
    Route::get('/', [ReviewController::class, 'index']);
});

/*
|==================================================================================
| 🔒 PROTECTED ROUTES (Authenticated Users)
|==================================================================================
*/

Route::middleware('auth:sanctum')->group(function () {
    
    // ===== 👤 User =====
    Route::get('/user', fn(Request $request) => $request->user());
    Route::get('/who-am-i', [AuthController::class, 'whoAmI']);
    Route::post('/logout', [AuthController::class, 'logout']);


    // ===== 🛒 Cart Merge =====
    Route::post('/cart/merge', [CartController::class, 'mergeCart']);


    // ===== 📦 Products Management =====
    Route::apiResource('products', ProductController::class)
        ->except(['index', 'show']);


    // ===== 🖼️ Product Images =====
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


    // ===== 📍 Addresses =====
    Route::apiResource('addresses', AddressController::class);


    // ===== 👥 Roles =====
    Route::apiResource('roles', RoleController::class);


    // ===== 🎁 Discounts Management =====
    Route::prefix('discounts')->group(function () {
        Route::post('/', [DiscountController::class, 'store']);
        Route::put('/{discount}', [DiscountController::class, 'update']);
        Route::delete('/{discount}', [DiscountController::class, 'destroy']);
    });


    // ===== 🚚 Shipping Features Management =====
    Route::post('variants/{variant}/shipping-features', [ProductVariantShippingFeatureController::class, 'store']);
    Route::put('shipping-features/{feature}', [ProductVariantShippingFeatureController::class, 'update']);
    Route::delete('shipping-features/{feature}', [ProductVariantShippingFeatureController::class, 'destroy']);


   
    /*
    |------------------------------------------------------------------
    | 🛡️ ADMIN ROUTES
    |------------------------------------------------------------------
    */
    
    Route::prefix('admin')->group(function () {
        
        // ===== 🖼️ Banners =====
        Route::prefix('banners')->group(function () {
            Route::get('/', [BannerController::class, 'adminIndex']);
            Route::post('/', [BannerController::class, 'store']);
            Route::get('/{banner}', [BannerController::class, 'show']);
            Route::post('/{banner}', [BannerController::class, 'update']);
            Route::delete('/{banner}', [BannerController::class, 'destroy']);
        });
        
        
        // ===== 📍 Banner Positions =====
        Route::apiResource('banner-positions', BannerPositionController::class);
        
        
        // ===== 🎫 Coupons =====
        Route::apiResource('coupons', CouponController::class);
    });


    /*
    |------------------------------------------------------------------
    | 📦 ORDER ROUTES
    |------------------------------------------------------------------
    */
    
    Route::prefix('orders')->group(function () {
        
        // 🛒 Checkout
        Route::post('/checkout', [OrderController::class, 'checkout']);
        
        // 📋 User Orders
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{order}', [OrderController::class, 'show']);
        
        // Actions
        Route::post('/{order}/cancel', [OrderController::class, 'cancel']);
        Route::post('/{order}/refund', [OrderController::class, 'refund']);
        Route::post('/{order}/pay', [OrderController::class, 'pay']);
    });
    // ⏰ Delivery Options
    Route::get('/delivery/options', [OrderController::class, 'deliveryOptions']);
});

Route::prefix('admin')->group(function () {
    // ... routes قبلی
    
    // ===== 🎯 Discount Campaigns =====
    Route::prefix('campaigns')->group(function () {
        Route::get('/', [DiscountCampaignController::class, 'adminIndex']);
        Route::post('/', [DiscountCampaignController::class, 'store']);
        Route::get('/{campaign}', [DiscountCampaignController::class, 'show']);
        Route::put('/{campaign}', [DiscountCampaignController::class, 'update']);
        Route::delete('/{campaign}', [DiscountCampaignController::class, 'destroy']);
    });
});

    // ===== ⭐ Reviews (Authenticated) =====
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
    Route::get('/products/{product}/my-review', [ReviewController::class, 'myReview']);