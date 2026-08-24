<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->cascadeOnDelete();
            $table->foreignId('product_variant_id')
                  ->nullable()
                  ->constrained('product_variants')
                  ->nullOnDelete();
            $table->string('product_title');
            $table->string('product_sku')->nullable();
            $table->string('product_image')->nullable();
            $table->json('variant_attributes')->nullable();
            $table->unsignedInteger('quantity');
            $table->decimal('base_price', 12, 2);         // Original price
            $table->decimal('final_price', 12, 2);        // After discount
            $table->decimal('discount_amount', 12, 2)     // Discount per unit
                  ->default(0);
            $table->decimal('total', 12, 2);
            
            $table->foreignId('discount_id')
                  ->nullable()
                  ->constrained('discounts')
                  ->nullOnDelete();
            
            
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('product_variant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};