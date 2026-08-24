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
                $table->foreignId('brand_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('short_description')->nullable();
                $table->longText('description')->nullable();
        
            
                $table->json('specifications')->nullable();
                $table->enum('status', [
                    'draft',
                    'active',
                    'inactive'
                ])->default('draft');
                $table->string('meta_title')->nullable();
                $table->string('meta_keywords')->nullable();
                $table->text('meta_description')->nullable();
                $table->unsignedBigInteger('view_count')->default(0);
                $table->decimal('rating', 3, 2)->default(0);
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
        
                $table->timestamps();
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