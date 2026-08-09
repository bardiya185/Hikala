<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banner_position_id')
                  ->constrained('banner_positions')
                  ->cascadeOnDelete();
        
            $table->string('title')->nullable();     // مثل: "همه چیز برای کودک"
            $table->string('subtitle')->nullable();  // مثل: "تا ۵۰٪ تخفیف"
            $table->string('image');                 // مسیر تصویر
            $table->string('mobile_image')->nullable(); // نسخه موبایل (اختیاری)
            $table->string('alt_text')->nullable();  // برای SEO
        
            // ✅ لینک - Polymorphic (میتونه به هر چیزی وصل بشه)
            $table->string('linkable_type')->nullable(); // Product, Category, Brand
            $table->unsignedBigInteger('linkable_id')->nullable();
            $table->string('custom_url')->nullable();    // اگه لینک خارجی/سفارشی
        
            // ✅ استایل (اختیاری - برای رنگ پس‌زمینه و ...)
            $table->string('background_color')->nullable(); // مثل: #E53E3E
            $table->string('text_color')->nullable();
        
            // ✅ زمانبندی
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
        
            // ✅ ترتیب و وضعیت
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
        
            // ✅ آمار
            $table->unsignedBigInteger('click_count')->default(0);
            $table->unsignedBigInteger('view_count')->default(0);
        
            $table->timestamps();
        
            $table->index(['banner_position_id', 'is_active']);
            $table->index(['linkable_type', 'linkable_id']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
