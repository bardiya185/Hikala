<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            
            // ================================================================
            // 🆔 Identifiers
            // ================================================================
            
            $table->id();
            
            // 🎫 Unique order code (e.g., "ORD-20241027-0001")
            $table->string('order_number')->unique();
            
            
            // ================================================================
            // 👤 User & Address
            // ================================================================
            
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            
            // 📍 Address (Snapshot - can't be null even if address is deleted)
            $table->foreignId('address_id')
                  ->nullable()
                  ->constrained('addresses')
                  ->nullOnDelete();
            
            
            // ================================================================
            // 📊 Statuses (using Enum)
            // ================================================================
            
            $table->enum('status', OrderStatus::values())
                  ->default(OrderStatus::PENDING->value);
            
            $table->enum('payment_status', PaymentStatus::values())
                  ->default(PaymentStatus::PENDING->value);
            
            $table->enum('payment_method', PaymentMethod::values());
            
            
            // ================================================================
            // 💰 Financial (all in decimal for precision)
            // ================================================================
            
            // Sum of base prices
            $table->decimal('subtotal', 12, 2)->default(0);
            
            // Products discount amount
            $table->decimal('discount_amount', 12, 2)->default(0);
            
            // Coupon discount amount
            $table->decimal('coupon_amount', 12, 2)->default(0);
            
            // Shipping cost
            $table->decimal('shipping_cost', 12, 2)->default(0);
            
            // Final total (customer pays this)
            $table->decimal('total_amount', 12, 2)->default(0);
            
            
            // ================================================================
            // 🎟️ Coupon
            // ================================================================
            
            $table->foreignId('coupon_id')
                  ->nullable()
                  ->constrained('coupons')
                  ->nullOnDelete();
            
            
            // ================================================================
            // 📝 Notes
            // ================================================================
            
            $table->text('customer_note')->nullable();
            $table->text('admin_note')->nullable();
            
            
            // ================================================================
            // 💳 Payment
            // ================================================================
            
            $table->string('transaction_id')->nullable();
            
            
            // ================================================================
            // ⏰ Timestamps for each status
            // ================================================================
            
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            
            
            // 📅 Standard timestamps
            $table->timestamps();
            
            
            // ================================================================
            // 🔍 Indexes (for query speed)
            // ================================================================
            
            $table->index('order_number');
            $table->index('status');
            $table->index('payment_status');
            $table->index(['user_id', 'status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};