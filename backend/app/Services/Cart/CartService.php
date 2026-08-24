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
        private CouponService $couponService,
        private CouponValidator $couponValidator
    ) {}
    public function getOrCreate(?User $user, ?string $sessionId): Cart
    {
        if ($user) {
            $userCart = Cart::firstOrCreate(
                ['user_id' => $user->id],
                []
            );
            
            if ($sessionId) {
                $this->mergeGuestCartIntoUserCart($userCart, $sessionId);
                $userCart->refresh();
            }
            
            return $userCart;
        }
    
        if ($sessionId) {
            return Cart::firstOrCreate(
                ['session_id' => $sessionId, 'user_id' => null],
                []
            );
        }
    
        $tempSessionId = 'temp-' . uniqid();
        return Cart::create([
            'session_id' => $tempSessionId,
            'user_id' => null,
        ]);
    }
    
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
                $newQuantity = $existingItem->quantity + $guestItem->quantity;
                $allowedQuantity = $this->getAllowedQuantity($existingItem->variant, $newQuantity);
                
                $existingItem->update([
                    'quantity' => $allowedQuantity
                ]);
            } else {
                $variant = $guestItem->variant;
                $allowedQuantity = $this->getAllowedQuantity($variant, $guestItem->quantity);
                
                $guestItem->update([
                    'cart_id' => $userCart->id,
                    'quantity' => $allowedQuantity,
                ]);
            }
        }
        
        $guestCart->delete();
    }
    public function addItem(
        Cart $cart,
        ProductVariant $variant,
        int $quantity = 1
    ): CartItem {
        return DB::transaction(function () use ($cart, $variant, $quantity) {
            $this->validateStock($variant, $quantity);
            $this->validateMaxOrderQuantity($variant, $quantity);
            $existingItem = $cart->items()
                ->where('product_variant_id', $variant->id)
                ->first();

            if ($existingItem) {
                $newQuantity = $existingItem->quantity + $quantity;
                $this->validateStock($variant, $newQuantity);
                $this->validateMaxOrderQuantity($variant, $newQuantity);
                
                $existingItem->update(['quantity' => $newQuantity]);
                return $existingItem->fresh();
            }
            $pricing = $this->discountService->calculate($variant);
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
    public function updateQuantity(CartItem $item, int $quantity): CartItem
    {
        if ($quantity < 1) {
            throw new \Exception('Quantity must be at least 1');
        }
        $this->validateStock($item->variant, $quantity);
        $this->validateMaxOrderQuantity($item->variant, $quantity);

        $item->update(['quantity' => $quantity]);
        return $item->fresh();
    }
    public function removeItem(CartItem $item): void
    {
        $item->delete();
    }
    public function clear(Cart $cart): void
    {
        DB::transaction(function () use ($cart) {
            $cart->items()->delete();
            $cart->update(['coupon_id' => null]);
        });
    }
    public function applyCoupon(Cart $cart, string $code): Cart
    {
        $coupon = $this->couponService->find($code);

        if (!$coupon) {
            throw new \Exception('Invalid coupon code');
        }

        $coupon->load('discount');
        $this->couponValidator->validate($coupon);

        if ($cart->user_id) {
            $this->couponValidator->validateForUser($coupon, $cart->user);
        }

        $cart->update(['coupon_id' => $coupon->id]);
        return $cart->fresh();
    }
    public function removeCoupon(Cart $cart): Cart
    {
        $cart->update(['coupon_id' => null]);
        return $cart->fresh();
    }
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

            $userCart = $this->getOrCreate($user, null);

            foreach ($guestCart->items as $guestItem) {
                $existingItem = $userCart->items()
                    ->where('product_variant_id', $guestItem->product_variant_id)
                    ->first();

                if ($existingItem) {
                    $newQuantity = $existingItem->quantity + $guestItem->quantity;
                    $allowedQuantity = $this->getAllowedQuantity($existingItem->variant, $newQuantity);
                    
                    $existingItem->update([
                        'quantity' => $allowedQuantity
                    ]);
                } else {
                    $variant = $guestItem->variant;
                    $allowedQuantity = $this->getAllowedQuantity($variant, $guestItem->quantity);
                    
                    $guestItem->update([
                        'cart_id' => $userCart->id,
                        'quantity' => $allowedQuantity,
                    ]);
                }
            }

            $guestCart->delete();
            return $userCart->fresh();
        });
    }

    /**
     * ✅ چک موجودی
     */
    private function validateStock(ProductVariant $variant, int $quantity): void
    {
        if ($variant->stock < $quantity) {
            throw new \Exception(
                "Insufficient stock. Available: {$variant->stock}"
            );
        }
    }

    /**
     * 🔥 چک محدودیت هر سفارش
     */
    private function validateMaxOrderQuantity(ProductVariant $variant, int $quantity): void
    {
        if (!$variant->max_order_quantity) {
            return; // 🎯 اگه محدودیت نداشت، مشکلی نیست
        }

        if ($quantity > $variant->max_order_quantity) {
            throw new \Exception(
                "Maximum {$variant->max_order_quantity} of this product allowed per order"
            );
        }
    }

    /**
     * 🎯 محاسبه بیشترین تعداد مجاز (کمترین بین stock و max_order_quantity)
     */
    private function getAllowedQuantity(ProductVariant $variant, int $requestedQuantity): int
    {
        $maxByStock = min($requestedQuantity, $variant->stock);
        if ($variant->max_order_quantity) {
            return min($maxByStock, $variant->max_order_quantity);
        }

        return $maxByStock;
    }
}