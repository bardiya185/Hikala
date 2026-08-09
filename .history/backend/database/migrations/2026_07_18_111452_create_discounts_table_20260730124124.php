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
            
            // 🔗 ارتباط با Campaign
            $table->foreignId('campaign_id')
                  ->nullable()
                  ->constrained('discount_campaigns')
                  ->nullOnDelete();
            
            // اطلاعات پایه
            $table->string('name');
            
            $table->enum('type', [
                'percent',
                'fixed',
            ]);
            
            $table->decimal('value', 12, 2);
            
            // ویژگی‌ها
            $table->boolean('stackable')->default(false);
            
            // زمان‌بندی
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            
            // محدودیت‌ها
            $table->unsignedInteger('quantity_limit')->nullable();
            $table->unsignedInteger('used_quantity')->default(0);
            
            // اولویت و وضعیت
            $table->unsignedInteger('priority')->default(0);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Indexes (بدون campaign_id چون constrained خودش می‌سازه)
            $table->index('starts_at');
            $table->index('ends_at');
            $table->index('is_active');
            $table->index('priority');
            $table->index(['is_active', 'starts_at', 'ends_at']);  // ✅ composite index
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};