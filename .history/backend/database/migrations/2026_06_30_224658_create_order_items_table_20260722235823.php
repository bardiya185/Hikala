<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            
            // ================================================================
            // 🆔 Identifiers
            // ================================================================
            
            $table->id();
            
            // 📦 Which order this item belongs to
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->cascadeOnDelete();
            
            // 🎯 Original product variant (nullable in case product is deleted)
            $table->foreignId('product_variant_id')
                  ->nullable()
                  ->constrained('product_variants')
                  ->nullOnDelete();
            
            
            // ================================================================
            // 📸 Product Snapshots (frozen at time of order)
            // ================================================================
            
            // Product info snapshot
            $table->string('product_title');
            $table->string('product_sku')->nullable();
            $table->string('product_image')->nullable();
            
            // Variant attributes snapshot (e.g., "Color: Red, Size: L")
            $table->json('variant_attributes')->nullable();
            
            
            // ================================================================
            // 🔢 Quantity & Pricing
            // ================================================================
            
            // How many were ordered
            $table->unsignedInteger('quantity');
            
            // 💰 Price snapshots
            $table->decimal('base_price', 12, 2);         // Original price
            $table->decimal('final_price', 12, 2);        // After discount
            $table->decimal('discount_amount', 12, 2)     // Discount per unit
                  ->default(0);
            
            // 💵 Line total (final_price × quantity)
            $table->decimal('total', 12, 2);
            
            
            // ================================================================
            // 🎁 Applied Discount (for reference/reporting)
            // ================================================================
            
            $table->foreignId('discount_id')
                  ->nullable()
                  ->constrained('discounts')
                  ->nullOnDelete();
            
            
            $table->timestamps();
            
            
            // ================================================================
            // 🔍 Indexes
            // ================================================================
            
            $table->index('order_id');
            $table->index('product_variant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};