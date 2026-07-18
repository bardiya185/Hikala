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
        Schema::create('coupons', function (Blueprint $table) {

            $table->id();
        
        
            // کد وارد شده توسط کاربر
            $table->string('code')
                ->unique();
        
        
            // ارتباط با Discount
            $table->foreignId('discount_id')
                ->constrained()
                ->cascadeOnDelete();
        
        
            // تعداد کل استفاده
            $table->unsignedInteger('usage_limit')
                ->nullable();
        
        
            // تعداد استفاده شده
            $table->unsignedInteger('used_count')
                ->default(0);
        
        
            // فعال بودن
            $table->boolean('is_active')
                ->default(true);
        
        
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
