<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\Discount\DiscountService;
use Illuminate\Support\Facades\DB;
use App\Services\Coupon\CouponService;
use App\Services\Coupon\CouponValidator;
class CartService
{
    public function __construct(
        private DiscountService $discountService,
        private CouponService $couponService,           // ⬅️ اضافه شد
        private CouponValidator $couponValidator   
    ) {}

    // ================================================================
    // 🛒 Get or Create Cart
    // ================================================================
    public function getOrCreate(?User $user, ?string $sessionId): Cart
    {
        // 🎯 اگه کاربر لاگین‌کرده هست
        if ($user) {
            
            // ابتدا سبد کاربر رو بگیر یا بساز
            $userCart = Cart::firstOrCreate(
                ['user_id' => $user->id],
                []
            );
            
            // 🔀 اگه session_id هم داشت، سبد مهمان رو merge کن
            if ($sessionId) {
                $this->mergeGuestCartIntoUserCart($userCart, $sessionId);
                $userCart->refresh();
            }
            
            return $userCart;
        }
    
        // 👤 اگه مهمان هست
        if ($sessionId) {
            return Cart::firstOrCreate(
                ['session_id' => $sessionId, 'user_id' => null],
                []
            );
        }
    
        // ⚠️ نه کاربر داریم، نه session_id
        // یه session_id موقت خودمون بسازیم
        $tempSessionId = 'temp-' . uniqid();
        return Cart::create([
            'session_id' => $tempSessionId,
            'user_id' => null,
        ]);
    }
    
    /**
     * 🔀 Merge guest cart items into user cart
     */
    private function mergeGuestCartIntoUserCart(Cart $userCart, string $sessionId): void
    {
        $guestCart = Cart::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->with('items')
            ->first();
        
        if (!$guestCart || $guestCart->items->isEmpty()) {
            return;
        }
        
        foreach ($guestCart->items as $guestItem) {
            $existingItem = $userCart->items()
                ->where('product_variant_id', $guestItem->product_variant_id)
                ->first();
    
            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $guestItem->quantity
                ]);
            } else {
                $guestItem->update(['cart_id' => $userCart->id]);
            }
        }
        
        $guestCart->delete();
    }

    // ================================================================
    // ➕ Add Item to Cart
    // ================================================================
    public function addItem(
        Cart $cart,
        ProductVariant $variant,
        int $quantity = 1
    ): CartItem {
        return DB::transaction(function () use ($cart, $variant, $quantity) {

            // ✅ چک موجودی
            if ($variant->stock < $quantity) {
                throw new \Exception("Insufficient stock. Available: {$variant->stock}");
            }

            // ✅ چک کن آیا این محصول قبلاً توی سبد هست؟
            $existingItem = $cart->items()
                ->where('product_variant_id', $variant->id)
                ->first();

            if ($existingItem) {
                // تعداد رو اضافه کن
                $newQuantity = $existingItem->quantity + $quantity;
                
                if ($variant->stock < $newQuantity) {
                    throw new \Exception("Insufficient stock. Available: {$variant->stock}");
                }
                
                $existingItem->update(['quantity' => $newQuantity]);
                return $existingItem->fresh();
            }

            // ✅ محاسبه قیمت با تخفیف
            $pricing = $this->discountService->calculate($variant);

            // ✅ ایجاد آیتم جدید
            return CartItem::create([
                'cart_id' => $cart->id,
                'product_variant_id' => $variant->id,
                'quantity' => $quantity,
                'base_price' => $pricing->basePrice,
                'final_price' => $pricing->price,
                'discount_amount' => $pricing->discountAmount,
                'discount_id' => $pricing->discount?->id,
            ]);
        });
    }

    // ================================================================
    // ✏️ Update Item Quantity
    // ================================================================
    public function updateQuantity(CartItem $item, int $quantity): CartItem
    {
        if ($quantity < 1) {
            throw new \Exception('Quantity must be at least 1');
        }

        // ✅ چک موجودی
        if ($item->variant->stock < $quantity) {
            throw new \Exception("Insufficient stock. Available: {$item->variant->stock}");
        }

        $item->update(['quantity' => $quantity]);
        return $item->fresh();
    }

    // ================================================================
    // ❌ Remove Item
    // ================================================================
    public function removeItem(CartItem $item): void
    {
        $item->delete();
    }

    // ================================================================
    // 🗑️ Clear Cart
    // ================================================================
    public function clear(Cart $cart): void
    {
        DB::transaction(function () use ($cart) {
            $cart->items()->delete();
            $cart->update(['coupon_id' => null]);
        });
    }

   // ================================================================
// 🎟️ Apply Coupon
// ================================================================
public function applyCoupon(Cart $cart, string $code): Cart
{
    // پیدا کردن کوپن
    $coupon = $this->couponService->find($code);

    if (!$coupon) {
        throw new \Exception('Invalid coupon code');
    }

    // بارگذاری discount
    $coupon->load('discount');

    // ولیدیت
    $this->couponValidator->validate($coupon);

    // ولیدیت برای کاربر
    if ($cart->user_id) {
        $this->couponValidator->validateForUser($coupon, $cart->user);
    }

    // ذخیره کوپن روی سبد
    $cart->update(['coupon_id' => $coupon->id]);

    return $cart->fresh();
}
    // ================================================================
    // 🔀 Merge Guest Cart with User Cart
    // ================================================================
    public function mergeGuestCart(User $user, string $sessionId): Cart
    {
        return DB::transaction(function () use ($user, $sessionId) {
            
            $guestCart = Cart::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->with('items')
                ->first();

            if (!$guestCart || $guestCart->items->isEmpty()) {
                return $this->getOrCreate($user, null);
            }

            // سبد کاربر رو بگیر یا بساز
            $userCart = $this->getOrCreate($user, null);

            // آیتم‌های مهمان رو منتقل کن
            foreach ($guestCart->items as $guestItem) {
                $existingItem = $userCart->items()
                    ->where('product_variant_id', $guestItem->product_variant_id)
                    ->first();

                if ($existingItem) {
                    // اگه بود، تعداد رو اضافه کن
                    $existingItem->update([
                        'quantity' => $existingItem->quantity + $guestItem->quantity
                    ]);
                } else {
                    // اگه نبود، منتقل کن
                    $guestItem->update(['cart_id' => $userCart->id]);
                }
            }

            // سبد مهمان رو پاک کن
            $guestCart->delete();

            return $userCart->fresh();
        });
    }
}