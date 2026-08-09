<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\Discount\DiscountService;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function __construct(
        private DiscountService $discountService
        private CouponService $couponService,           // ⬅️ اضافه شد
        private CouponValidator $couponValidator   
    ) {}

    // ================================================================
    // 🛒 Get or Create Cart
    // ================================================================
    public function getOrCreate(?User $user, ?string $sessionId): Cart
    {
        // اگه کاربر لاگین‌کرده هست
        if ($user) {
            return Cart::firstOrCreate(
                ['user_id' => $user->id],
                []
            );
        }

        // اگه مهمان هست
        if ($sessionId) {
            return Cart::firstOrCreate(
                ['session_id' => $sessionId, 'user_id' => null],
                []
            );
        }

        throw new \Exception('User or session ID required');
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
        $coupon = Coupon::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            throw new \Exception('Invalid coupon code');
        }

        // ✅ چک تاریخ انقضا
        if ($coupon->expires_at && now()->gt($coupon->expires_at)) {
            throw new \Exception('Coupon expired');
        }

        // ✅ چک حداقل مبلغ (اگه داشته باشه)
        if (isset($coupon->min_cart_amount) 
            && $cart->total_before_coupon < $coupon->min_cart_amount) {
            throw new \Exception("Minimum cart amount: {$coupon->min_cart_amount}");
        }

        $cart->update(['coupon_id' => $coupon->id]);
        return $cart->fresh();
    }

    // ================================================================
    // ❌ Remove Coupon
    // ================================================================
    public function removeCoupon(Cart $cart): Cart
    {
        $cart->update(['coupon_id' => null]);
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