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

            // نام کمپین
            $table->string('name');

            // درصد یا مبلغ
            $table->enum('type', [
                'percent',
                'fixed',
            ]);

            // مقدار تخفیف
            $table->decimal('value', 12, 2);

            $table->enum('target_type', [

                'global',

                'brand',

                'category',

                'product',

                'variant',

            ])->default('variant')->after('value');

            // شروع
            $table->timestamp('starts_at')->nullable();

            // پایان
            $table->timestamp('ends_at')->nullable();

            // محدودیت تعداد
            $table->unsignedInteger('quantity_limit')->nullable();

            // اولویت
            $table->unsignedInteger('priority')->default(0);

            // شگفت انگیز
            $table->boolean('is_flash_sale')->default(false);

            // فعال
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('starts_at');
            $table->index('ends_at');
            $table->index('is_flash_sale');
            $table->index('is_active');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};