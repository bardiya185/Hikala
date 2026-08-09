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
            
            // اطلاعات پایه
            $table->string('name');                           // مثل: "Flash Sale"
            $table->string('slug')->unique();                 // مثل: "flash-sale"
            $table->text('description')->nullable();
            
            // ظاهر و branding
            $table->string('icon')->nullable();               // مثل: "⚡"
            $table->string('color', 7)->nullable();           // مثل: "#DC2626"
            $table->string('banner_image')->nullable();       // مسیر عکس بنر
            
            // ترتیب و وضعیت
            $table->unsignedInteger('priority')->default(0);  // اولویت (بیشتر = مهم‌تر)
            $table->boolean('is_active')->default(true);
            
            // زمان‌بندی
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active', 'priority']);
            $table->index('slug');
            $table->index('starts_at');
            $table->index('ends_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_campaigns');
    }
};