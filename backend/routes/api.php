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
use App\Http\Controllers\Api\DiscountCampaignController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductImageController;
use App\Http\Controllers\Api\ProductVariantShippingFeatureController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserRoleController;
use App\Http\Controllers\Api\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|==================================================================================
| 🌐 PUBLIC ROUTES (No Authentication)
|==================================================================================
*/

// ===== 🔐 Authentication =====
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/check-otp', [AuthController::class, 'checkOtp']);
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

// ===== 🔐 Who Am I (Optional Auth) =====
Route::get('/who-am-i', [AuthController::class, 'whoAmI'])
    ->middleware('optional.auth');

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

// ===== 🎯 Discount Campaigns (Public) =====
Route::prefix('campaigns')->group(function () {
    Route::get('/', [DiscountCampaignController::class, 'index']);
    Route::get('/{slug}', [DiscountCampaignController::class, 'showBySlug']);
    Route::get('/{slug}/products', [DiscountCampaignController::class, 'products']);
});

// ===== ⭐ Product Reviews (Public) =====
Route::get('/products/{product}/reviews', [ReviewController::class, 'index']);


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


/*
|==================================================================================
| 🔒 AUTHENTICATED ROUTES (Any Logged-in User)
|==================================================================================
*/

Route::middleware('auth:sanctum')->group(function () {

    // ===== 👤 User =====
    Route::get('/user', fn(Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);

    // ===== 🛒 Cart Merge =====
    Route::post('/cart/merge', [CartController::class, 'mergeCart']);

    // ===== 📍 Addresses =====
    Route::apiResource('addresses', AddressController::class);

    // ===== ⭐ Reviews (User) =====
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
    Route::get('/products/{product}/my-review', [ReviewController::class, 'myReview']);
    Route::post('/reviews/{review}/react', [ReviewController::class, 'react']);

    // ===== 📦 Orders (User) =====
    Route::prefix('orders')->group(function () {
        Route::post('/checkout', [OrderController::class, 'checkout']);
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::post('/{order}/cancel', [OrderController::class, 'cancel']);
        Route::post('/{order}/pay', [OrderController::class, 'pay']);
    });

    Route::get('/delivery/options', [OrderController::class, 'deliveryOptions']);
});


/*
|==================================================================================
| 🛡️ ADMIN ROUTES (Role/Permission Protected)
|==================================================================================
*/

Route::prefix('admin')
    ->middleware(['auth:sanctum', 'role:super-admin|admin'])
    ->group(function () {

    // ===== 📦 Products Management =====
    Route::prefix('products')->middleware('permission:create-products')->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'destroy'])
            ->middleware('permission:delete-products');
    });

    // ===== 🖼️ Product Images =====
    Route::prefix('products/{product}/images')
        ->middleware('permission:update-products')
        ->scopeBindings()
        ->group(function () {
            Route::post('/', [ProductImageController::class, 'store']);
            Route::delete('/{image}', [ProductImageController::class, 'destroy']);
            Route::put('/{image}/main', [ProductImageController::class, 'setMain']);
            Route::put('/reorder', [ProductImageController::class, 'reorder']);
        });

    // ===== 🗂️ Categories Management =====
    Route::prefix('categories')->middleware('permission:create-categories')->group(function () {
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{category}', [CategoryController::class, 'update']);
        Route::delete('/{category}', [CategoryController::class, 'destroy'])
            ->middleware('permission:delete-categories');
    });

    // ===== 🏷️ Brands Management =====
    Route::prefix('brands')->middleware('permission:create-brands')->group(function () {
        Route::post('/', [BrandController::class, 'store']);
        Route::put('/{brand}', [BrandController::class, 'update']);
        Route::delete('/{brand}', [BrandController::class, 'destroy'])
            ->middleware('permission:delete-brands');
    });

    // ===== 🎨 Attributes Management =====
    Route::prefix('attributes')->middleware('permission:create-products')->group(function () {
        Route::post('/', [AttributeController::class, 'store']);
        Route::put('/{attribute}', [AttributeController::class, 'update']);
        Route::delete('/{attribute}', [AttributeController::class, 'destroy']);
    });

    // ===== 🎁 Discounts Management =====
    Route::prefix('discounts')->middleware('permission:create-discounts')->group(function () {
        Route::post('/', [DiscountController::class, 'store']);
        Route::put('/{discount}', [DiscountController::class, 'update']);
        Route::delete('/{discount}', [DiscountController::class, 'destroy'])
            ->middleware('permission:delete-discounts');
    });

    // ===== 🚚 Shipping Features Management =====
    Route::middleware('permission:update-products')->group(function () {
        Route::post('variants/{variant}/shipping-features', [ProductVariantShippingFeatureController::class, 'store']);
        Route::put('shipping-features/{feature}', [ProductVariantShippingFeatureController::class, 'update']);
        Route::delete('shipping-features/{feature}', [ProductVariantShippingFeatureController::class, 'destroy']);
    });

    // ===== 🖼️ Banners =====
    Route::prefix('banners')->middleware('permission:create-banners')->group(function () {
        Route::get('/', [BannerController::class, 'adminIndex']);
        Route::post('/', [BannerController::class, 'store']);
        Route::get('/{banner}', [BannerController::class, 'show']);
        Route::post('/{banner}', [BannerController::class, 'update']);
        Route::delete('/{banner}', [BannerController::class, 'destroy'])
            ->middleware('permission:delete-banners');
    });

    // ===== 📍 Banner Positions =====
    Route::apiResource('banner-positions', BannerPositionController::class)
        ->middleware('permission:create-banners');

    // ===== 🎫 Coupons =====
    Route::apiResource('coupons', CouponController::class)
        ->middleware('permission:create-coupons');

    // ===== 🎯 Discount Campaigns =====
    Route::prefix('campaigns')->middleware('permission:create-campaigns')->group(function () {
        Route::get('/', [DiscountCampaignController::class, 'adminIndex']);
        Route::post('/', [DiscountCampaignController::class, 'store']);
        Route::get('/{campaign}', [DiscountCampaignController::class, 'show']);
        Route::put('/{campaign}', [DiscountCampaignController::class, 'update']);
        Route::delete('/{campaign}', [DiscountCampaignController::class, 'destroy'])
            ->middleware('permission:delete-campaigns');
    });

    // ===== ⭐ Reviews Management =====
    Route::prefix('reviews')->middleware('permission:approve-reviews')->group(function () {
        Route::get('/', [ReviewController::class, 'adminIndex']);
        Route::post('/{review}/approve', [ReviewController::class, 'approve']);
        Route::post('/{review}/reject', [ReviewController::class, 'reject']);
    });

    // ===== 📦 Orders Management =====
    Route::prefix('orders')->middleware('permission:view-orders')->group(function () {
        Route::post('/{order}/refund', [OrderController::class, 'refund'])
            ->middleware('permission:refund-orders');
    });
});


/*
|==================================================================================
| 👑 SUPER ADMIN ROUTES
|==================================================================================
*/

Route::prefix('admin')
    ->middleware(['auth:sanctum', 'role:super-admin'])
    ->group(function () {

    // ===== 👥 Roles Management =====
    Route::apiResource('roles', RoleController::class);

    // ===== 👤 User Role Management =====
    Route::prefix('users')->group(function () {
        Route::get('/{user}/roles', [UserRoleController::class, 'index']);
        Route::put('/{user}/role', [UserRoleController::class, 'sync']);
        Route::post('/{user}/roles', [UserRoleController::class, 'attach']);
        Route::delete('/{user}/roles/{role}', [UserRoleController::class, 'destroy']);
    });

    Route::prefix('wishlist')->group(function () {
        Route::get('/', [WishlistController::class, 'index']);
        Route::delete('/', [WishlistController::class, 'clear']);
        Route::post('/{product}', [WishlistController::class, 'store']);
        Route::delete('/{product}', [WishlistController::class, 'destroy']);
        Route::post('/{product}/toggle', [WishlistController::class, 'toggle']);
        Route::get('/{product}/check', [WishlistController::class, 'check']);
    });
});