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
            
            // Display Info
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // Branding
            $table->string('icon', 10)->nullable();
            $table->string('color', 7)->nullable();
            $table->string('banner_image')->nullable();
            
            // Scheduling (⏰ زمان کل کمپین)
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            
            // Display Priority (ترتیب نمایش)
            $table->unsignedInteger('priority')->default(0);
            
            // Status
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
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