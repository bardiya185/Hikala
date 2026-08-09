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
    
            Schema::create('products', function (Blueprint $table) {
                $table->id();
        
                // برند
                $table->foreignId('brand_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();
        
                // اطلاعات اصلی
                $table->string('title');
                $table->string('slug')->unique();
        
                // توضیحات
                $table->text('short_description')->nullable();
                $table->longText('description')->nullable();
        
            
                $table->json('specifications')->nullable();
        
                // وضعیت
                $table->enum('status', [
                    'draft',
                    'active',
                    'inactive'
                ])->default('draft');
        
                // سئو
                $table->string('meta_title')->nullable();
                $table->string('meta_keywords')->nullable();
                $table->text('meta_description')->nullable();
        
                // آمار
                $table->unsignedBigInteger('view_count')->default(0);

                //امتیاز
                $table->decimal('rating', 3, 2)->default(0)->change();
        
                // ترتیب نمایش
                $table->unsignedInteger('sort_order')->default(0);
        
                // فعال بودن
                $table->boolean('is_active')->default(true);
        
                $table->timestamps();
        
                // ایندکس‌ها
                $table->index('brand_id');
                $table->index('status');
                $table->index('is_active');
                $table->index('sort_order');
            });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};