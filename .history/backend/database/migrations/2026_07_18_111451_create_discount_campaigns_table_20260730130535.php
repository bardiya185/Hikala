<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discount_campaigns', function (Blueprint $table) {
            $table->id();
            
            // 📝 اطلاعات پایه
            $table->string('name');                    // "Flash Sale"
            $table->string('slug')->unique();          // "flash-sale"
            $table->text('description')->nullable();
            
            // 🎨 ظاهر (برای UI)
            $table->string('icon', 10)->nullable();    // "⚡"
            $table->string('color', 7)->nullable();    // "#DC2626"
            $table->string('banner_image')->nullable();
            
            // 📊 مدیریت
            $table->unsignedInteger('priority')->default(0);  // ترتیب نمایش
            $table->boolean('is_active')->default(true);
            
            // ⏰ زمان‌بندی کل کمپین
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            
            $table->timestamps();
            
            $table->index(['is_active', 'priority']);
            $table->index('slug');
        });
        
         }

    public function down(): void
    {
        Schema::dropIfExists('discount_campaigns');
    }
};