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
            
            // ═══════════════════════════════════════════════════
            // 🔗 ارتباط با کمپین (اختیاری)
            // ⭐ اگه به کمپین متصل باشه، زمان از کمپین میاد
            // ═══════════════════════════════════════════════════
            $table->foreignId('campaign_id')
                  ->nullable()
                  ->constrained('discount_campaigns')
                  ->nullOnDelete();
            
            
            // ═══════════════════════════════════════════════════
            // 📝 اسم داخلی (برای مدیریت)
            // ⭐ فقط برای شناسایی توی پنل ادمین
            // ═══════════════════════════════════════════════════
            $table->string('name');
            
            
            // ═══════════════════════════════════════════════════
            // 💰 قانون تخفیف (Discount Rule)
            // ⭐ اصلی‌ترین قسمت
            // ═══════════════════════════════════════════════════
            $table->enum('type', ['percent', 'fixed']);
            $table->decimal('value', 12, 2);
            $table->boolean('stackable')->default(false);
            
            
            // ═══════════════════════════════════════════════════
            // 🔢 محدودیت استفاده (Usage Limits)
            // ═══════════════════════════════════════════════════
            $table->unsignedInteger('quantity_limit')->nullable();
            $table->unsignedInteger('used_quantity')->default(0);
            
            
            // ═══════════════════════════════════════════════════
            // 📊 اولویت اعمال (Application Priority)
            // ⭐ وقتی چند تخفیف روی یه محصول هست، کدوم اول اعمال بشه؟
            // ═══════════════════════════════════════════════════
            $table->unsignedInteger('priority')->default(0);
            
            
            // ═══════════════════════════════════════════════════
            // ✅ وضعیت این تخفیف خاص
            // ═══════════════════════════════════════════════════
            $table->boolean('is_active')->default(true);
            
            
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active', 'priority']);
            $table->index('campaign_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};