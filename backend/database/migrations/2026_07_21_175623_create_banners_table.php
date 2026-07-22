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
            
            // محتوا
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('image');
            $table->string('mobile_image')->nullable();
            $table->string('alt_text')->nullable();
            
            // لینک (فقط custom_url!)
            $table->string('custom_url')->nullable();
            
            // استایل
            $table->string('background_color')->nullable();
            $table->string('text_color')->nullable();
            
            // زمانبندی
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            
            // مدیریت
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            $table->index(['banner_position_id', 'is_active']);
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
