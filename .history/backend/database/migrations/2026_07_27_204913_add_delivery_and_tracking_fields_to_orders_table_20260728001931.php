<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            
            // ═════════════════════════════════════
            // 📅 Delivery Preference (Customer picks)
            // ═════════════════════════════════════
            $table->date('preferred_delivery_date')
                  ->nullable()
                  ->after('customer_note');
            
            $table->string('preferred_delivery_time_slot')
                  ->nullable()
                  ->after('preferred_delivery_date');
            
            
            // ═════════════════════════════════════
            // 📦 Estimated Delivery (Admin sets)
            // ═════════════════════════════════════
            $table->timestamp('estimated_delivery_from')
                  ->nullable()
                  ->after('preferred_delivery_time_slot');
            
            $table->timestamp('estimated_delivery_to')
                  ->nullable()
                  ->after('estimated_delivery_from');
            
            
            // ═════════════════════════════════════
            // 🚚 Shipping Tracking
            // ═════════════════════════════════════
            $table->string('tracking_code')
                  ->nullable()
                  ->after('estimated_delivery_to');
            
            $table->string('shipping_carrier')
                  ->nullable()
                  ->after('tracking_code');
            
            
            // ═════════════════════════════════════
            // ❌ Cancel & Refund Reasons
            // ═════════════════════════════════════
            $table->text('cancel_reason')
                  ->nullable()
                  ->after('canceled_at');
            
            $table->text('refund_reason')
                  ->nullable()
                  ->after('refunded_at');
            
            
            // ═════════════════════════════════════
            // 🔍 Indexes
            // ═════════════════════════════════════
            $table->index('tracking_code');
            $table->index('preferred_delivery_date');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['tracking_code']);
            $table->dropIndex(['preferred_delivery_date']);
            
            $table->dropColumn([
                'preferred_delivery_date',
                'preferred_delivery_time_slot',
                'estimated_delivery_from',
                'estimated_delivery_to',
                'tracking_code',
                'shipping_carrier',
                'cancel_reason',
                'refund_reason',
            ]);
        });
    }
};