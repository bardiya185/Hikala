<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('cart_id')
                  ->constrained('carts')
                  ->cascadeOnDelete();
            
            $table->foreignId('product_variant_id')
                  ->constrained('product_variants')
                  ->cascadeOnDelete();
            
            // تعداد
            $table->unsignedInteger('quantity')->default(1);
            
            // ✅ Snapshot قیمت‌ها (در لحظه افزودن ثابت می‌شن)
            $table->decimal('base_price', 12, 2);
            $table->decimal('final_price', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            
            // ✅ تخفیف اعمال شده (برای گزارش)
            $table->foreignId('discount_id')
                  ->nullable()
                  ->constrained('discounts')
                  ->nullOnDelete();
            
            $table->timestamps();
            
            // ✅ یه کاربر نباید دو تا آیتم یکسان داشته باشه
            $table->unique(['cart_id', 'product_variant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};