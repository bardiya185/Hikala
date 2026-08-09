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
            
            // ═══════════════════════════════════════════════════
            // 📝 اطلاعات نمایشی (Display Info)
            // ═══════════════════════════════════════════════════
            $table->string('name');                    // "Flash Sale"
            $table->string('slug')->unique();          // "flash-sale"
            $table->text('description')->nullable();
            
            
            // ═══════════════════════════════════════════════════
            // 🎨 برندینگ (Branding)
            // ═══════════════════════════════════════════════════
            $table->string('icon', 10)->nullable();    // "⚡"
            $table->string('color', 7)->nullable();    // "#DC2626"
            $table->string('banner_image')->nullable();
            
            
            // ═══════════════════════════════════════════════════
            // ⏰ زمان‌بندی (Scheduling) 
            // ⭐ این کمپین کِی نمایش داده بشه؟
            // ═══════════════════════════════════════════════════
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            
            
            // ═══════════════════════════════════════════════════
            // 📊 اولویت نمایش (Display Priority)
            // ⭐ کدوم کمپین اول در صفحه اصلی نشون داده بشه؟
            // ═══════════════════════════════════════════════════
            $table->unsignedInteger('priority')->default(0);
            
            
            // ═══════════════════════════════════════════════════
            // ✅ وضعیت (Status)
            // ═══════════════════════════════════════════════════
            $table->boolean('is_active')->default(true);
            
            
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active', 'priority']);
            $table->index('slug');
            $table->index(['starts_at', 'ends_at']);
        });

         }

    public function down(): void
    {
        Schema::dropIfExists('discount_campaigns');
    }
};