<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {

            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            // شناسه داخلی کالا
            $table->string('sku')->unique();

            // بارکد
            $table->string('barcode')->nullable();

            // قیمت
            $table->decimal('price', 15, 0);

            // قیمت با تخفیف
            $table->decimal('sale_price', 15, 0)->nullable();

            // موجودی
            $table->unsignedInteger('stock')->default(0);

            // وزن (گرم)
            $table->unsignedInteger('weight')->nullable();

            // فعال یا غیرفعال
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('product_id');
            $table->index('sku');
            $table->index('price');
            $table->index('stock');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};