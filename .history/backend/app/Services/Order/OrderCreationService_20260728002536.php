<?php

namespace App\Services\Order;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\Cart\CartService;
use App\Services\Discount\DiscountUsageService;
use App\Services\Coupon\CouponUsageService;
use Illuminate\Support\Facades\DB;
use App\Enums\ShippingMethod;
use App\Services\Shipping\ShippingCalculator;

/**
 * 🛒 Converts Cart → Order
 * 
 * This is the heart of checkout process
 */
class OrderCreationService
{
    // 💰 هزینه ارسال ثابت (بعداً می‌تونی داینامیک کنی)
    public function __construct(
        private OrderNumberGenerator $numberGenerator,
        private OrderStatusService $statusService,
        private CartService $cartService,
        private DiscountUsageService $discountUsageService,
        private CouponUsageService $couponUsageService,
        private ShippingCalculator $shippingCalculator,  // ⬅️ اضافه
    ) {}

    /**
     * 🎯 Create order from cart
     */
    public function createFromCart(
        Cart $cart,
        User $user,
        Address $address,
        PaymentMethod $paymentMethod,
        ?string $customerNote = null,
        ShippingMethod $shippingMethod = ShippingMethod::STANDARD  // ⬅️ اضافه
    ): Order {
        
        $this->validateCart($cart);
        $this->validateAddress($address, $user);
        $this->validateStock($cart);
        
        return DB::transaction(function () use (
            $cart, $user, $address, $paymentMethod, $customerNote, $shippingMethod
        ) {
            
            // 1️⃣ ساخت سفارش (با shipping method)
            $order = $this->createOrder(
                $cart, $user, $address, $paymentMethod, $customerNote, $shippingMethod
            );
            
            // 2️⃣ کپی آیتم‌ها
            $this->createOrderItems($order, $cart);
            
            // 3️⃣ کاهش موجودی
            $this->reduceStock($cart);
            
            // 4️⃣ مصرف تخفیف‌ها
            $this->consumeDiscounts($cart, $user);
            
            // 5️⃣ ثبت تاریخچه
            $this->statusService->logInitialStatus($order, $user);
            
            // 6️⃣ خالی کردن سبد
            $this->cartService->clear($cart);
            
            return $order->fresh(['items', 'address', 'statusHistory']);
        });
    }

    // ================================================================
    // 🔒 Private Methods
    // ================================================================

    private function validateCart(Cart $cart): void
    {
        if ($cart->items->isEmpty()) {
            throw new \Exception('Cart is empty');
        }
    }

    private function validateAddress(Address $address, User $user): void
    {
        if ($address->user_id !== $user->id) {
            throw new \Exception('This address does not belong to you');
        }
    }

    private function validateStock(Cart $cart): void
    {
        foreach ($cart->items as $item) {
            if ($item->variant->stock < $item->quantity) {
                throw new \Exception(
                    "Insufficient stock for {$item->variant->product->title}. " .
                    "Available: {$item->variant->stock}"
                );
            }
        }
    }

    private function createOrder(
        Cart $cart,
        User $user,
        Address $address,
        PaymentMethod $paymentMethod,
        ?string $customerNote,
        ShippingMethod $shippingMethod = ShippingMethod::STANDARD,
        ?string $preferredDeliveryDate = null,
        ?DeliveryTimeSlot $preferredTimeSlot = null
    ): Order {
        
        $subtotal = $cart->subtotal;
        $productsDiscount = $cart->products_discount;
        $couponDiscount = $cart->coupon_discount;
        
        // ✅ محاسبه هوشمند shipping
        $cartTotalAfterDiscount = $subtotal - $productsDiscount - $couponDiscount;
        $shippingCost = $this->shippingCalculator->calculate(
            $cartTotalAfterDiscount,
            $shippingMethod
        );
        
        $totalAmount = $cartTotalAfterDiscount + $shippingCost;
        
        return Order::create([
            'order_number' => $this->numberGenerator->generate(),
            'user_id' => $user->id,
            'address_id' => $address->id,
            'status' => OrderStatus::PENDING,
            'payment_status' => PaymentStatus::PENDING,
            'payment_method' => $paymentMethod,
            'subtotal' => $subtotal,
            'discount_amount' => $productsDiscount,
            'coupon_amount' => $couponDiscount,
            'shipping_cost' => $shippingCost,
            'total_amount' => max(0, $totalAmount),
            'coupon_id' => $cart->coupon_id,
            'customer_note' => $customerNote,
            'preferred_delivery_date' => $preferredDeliveryDate,
            'preferred_delivery_time_slot' => $preferredTimeSlot,
        ]);
    }

    private function createOrderItems(Order $order, Cart $cart): void
    {
        foreach ($cart->items as $cartItem) {
            $variant = $cartItem->variant;
            $product = $variant->product;
            
            // 📸 Snapshot attributes
            $attributes = $variant->attributeValues->map(fn($av) => [
                'attribute' => $av->attribute?->name,
                'value' => $av->value,
            ])->toArray();
            
            // 📸 Get main image
            $mainImage = $product?->images?->firstWhere('is_main', true);
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_variant_id' => $variant->id,
                
                // 📸 Snapshots
                'product_title' => $product?->title ?? 'Unknown',
                'product_sku' => $variant->sku,
                'product_image' => $mainImage?->image_path,
                'variant_attributes' => $attributes,
                
                // Numbers
                'quantity' => $cartItem->quantity,
                'base_price' => $cartItem->base_price,
                'final_price' => $cartItem->final_price,
                'discount_amount' => $cartItem->discount_amount,
                'total' => $cartItem->final_price * $cartItem->quantity,
                'discount_id' => $cartItem->discount_id,
            ]);
        }
    }

    private function reduceStock(Cart $cart): void
    {
        foreach ($cart->items as $item) {
            $item->variant->decrement('stock', $item->quantity);
        }
    }

    private function consumeDiscounts(Cart $cart, User $user): void
    {
        // مصرف تخفیف‌های محصولات
        foreach ($cart->items as $item) {
            if ($item->discount_id) {
                try {
                    $this->discountUsageService->consume(
                        $item->discount,
                        $user,
                        $item->quantity
                    );
                } catch (\Exception $e) {
                    // Log but don't fail the order
                    \Log::warning("Discount consume failed: " . $e->getMessage());
                }
            }
        }
        
        // مصرف کوپن
        if ($cart->coupon) {
            try {
                $this->couponUsageService->consume(
                    $cart->coupon,
                    $user,
                    1
                );
            } catch (\Exception $e) {
                \Log::warning("Coupon consume failed: " . $e->getMessage());
            }
        }
    }
}