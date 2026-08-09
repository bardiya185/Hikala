<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // روابط
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('product_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // محتوا
            $table->text('body');
            $table->unsignedTinyInteger('rating'); // 1 تا 5

            // مزایا و معایب
            $table->json('advantages')->nullable();
            $table->json('disadvantages')->nullable();

            // وضعیت تایید
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending');

            // آیا خریدار هست؟
            $table->boolean('is_buyer')->default(false);

            $table->timestamps();

            // هر کاربر فقط یک نظر برای هر محصول
            $table->unique(['user_id', 'product_id']);

            // ایندکس‌ها
            $table->index(['product_id', 'status']);
            $table->index(['user_id']);
            $table->index('rating');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};