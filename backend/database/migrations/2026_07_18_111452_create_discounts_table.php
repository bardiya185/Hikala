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
            $table->foreignId('campaign_id')
                  ->nullable()
                  ->constrained('discount_campaigns')
                  ->nullOnDelete();
            $table->string('name');
            $table->enum('type', ['percent', 'fixed']);
            $table->decimal('value', 12, 2);
            $table->boolean('stackable')->default(false);
            $table->unsignedInteger('quantity_limit')->nullable();
            $table->unsignedInteger('used_quantity')->default(0);
            $table->unsignedInteger('priority')->default(0);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            $table->index(['is_active', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};