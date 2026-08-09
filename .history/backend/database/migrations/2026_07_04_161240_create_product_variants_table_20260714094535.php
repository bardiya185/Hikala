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
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->string('sku')->unique();
                $table->string('barcode')->nullable();
                $table->decimal('price', 15, 0);
                $table->decimal('sale_price', 15, 0)->nullable();
                $table->unsignedInteger('stock')->default(0);
                $table->unsignedInteger('weight')->nullable();
                
                // ✅ اصلاح: بدون after
                $table->boolean('is_active')->default(true);
                $table->boolean('is_default')->default(false);
        
                $table->timestamps();
        
                $table->index('product_id');
                $table->index('sku');
                $table->index('is_active');
            });
        }
    

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};