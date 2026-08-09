<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\DeliveryTimeSlot;
use App\Enums\ShippingCarrier;
use 

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'address_id',
        'status',
        'payment_status',
        'payment_method',
        'subtotal',
        'discount_amount',
        'coupon_amount',
        'shipping_cost',
        'total_amount',
        'coupon_id',
        'customer_note',
        'admin_note',
        'transaction_id',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'preferred_delivery_date',
        'preferred_delivery_time_slot',
        'estimated_delivery_from',
        'estimated_delivery_to',
        'tracking_code',
        'shipping_carrier',
        'cancel_reason',
        'refund_reason',
        'canceled_at',
        'refunded_at',
    ];

    protected $casts = [
        // ✅ Enum casting (خودکار تبدیل می‌کنه)
        'status' => OrderStatus::class,
        'payment_status' => PaymentStatus::class,
        'payment_method' => PaymentMethod::class,
        
        // 💰 Money casting
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'coupon_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        
        // ⏰ Timestamps
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'canceled_at' => 'datetime',
        'refunded_at' => 'datetime',

        'preferred_delivery_date' => 'date',
        'preferred_delivery_time_slot' => DeliveryTimeSlot::class, 
        'estimated_delivery_from' => 'datetime',
        'estimated_delivery_to' => 'datetime',
        'shipping_carrier' => ShippingCarrier::class,
    ];

    // ================================================================
    // 🔗 Relationships
    // ================================================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)
                    ->orderBy('created_at', 'asc');
    }

    // ================================================================
    // 🎯 Accessors
    // ================================================================

    /**
     * 🏷️ Formatted order number
     */
    public function getFormattedOrderNumberAttribute(): string
    {
        return $this->order_number;
    }

    /**
     * 📊 Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    /**
     * 📊 Get status color
     */
    public function getStatusColorAttribute(): string
    {
        return $this->status->color();
    }

    /**
     * 🔢 Total items count
     */
    public function getItemsCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    // ================================================================
    // ✅ Helper Methods
    // ================================================================

    /**
     * ❌ Can this order be canceled?
     */
    public function canBeCanceled(): bool
    {
        return $this->status->canBeCanceled();
    }

    /**
     * 🔄 Can this order be refunded?
     */
    public function canBeRefunded(): bool
    {
        return $this->status->canBeRefunded();
    }

    /**
     * ✅ Is this order final?
     */
    public function isFinal(): bool
    {
        return $this->status->isFinal();
    }

    /**
     * 💰 Is this order paid?
     */
    public function isPaid(): bool
    {
        return $this->status->isPaid();
    }

    // ================================================================
    // 🔍 Scopes
    // ================================================================

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWithStatus($query, OrderStatus $status)
    {
        return $query->where('status', $status->value);
    }

    public function scopePaid($query)
    {
        return $query->whereIn('status', [
            OrderStatus::PAID->value,
            OrderStatus::PROCESSING->value,
            OrderStatus::SHIPPED->value,
            OrderStatus::DELIVERED->value,
        ]);
    }
}