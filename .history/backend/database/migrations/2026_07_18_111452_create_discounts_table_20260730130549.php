<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            
            // 🔗 اتصال به کمپین (اختیاری)
            $table->foreignId('campaign_id')
                  ->nullable()
                  ->constrained('discount_campaigns')
                  ->nullOnDelete();
            
            // 📝 اطلاعات پایه (اسم داخلی برای مدیریت)
            $table->string('name');  // "iPhone 50% Off" (اسم داخلی)
            
            // 💰 قانون تخفیف
            $table->enum('type', ['percent', 'fixed']);
            $table->decimal('value', 12, 2);
            $table->boolean('stackable')->default(false);
            
            // 🔢 محدودیت تعداد استفاده
            $table->unsignedInteger('quantity_limit')->nullable();
            $table->unsignedInteger('used_quantity')->default(0);
            
            // 📊 اولویت (وقتی چند تخفیف روی یه محصول اعمال میشه)
            $table->unsignedInteger('priority')->default(0);
            
            // ⏰ زمان‌بندی (اگه با کمپین متفاوته)
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            
            // 🎯 وضعیت
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            $table->index(['is_active', 'starts_at', 'ends_at']);
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};