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
        Schema::create('attributes', function (Blueprint $table) {
            Schema::create('attribute_values', function (Blueprint $table) {
                $table->id();
            
                $table->foreignId('attribute_id')
                    ->constrained()
                    ->cascadeOnDelete();
            
                $table->string('value');
            
                $table->integer('sort_order')->default(0);
            
                $table->boolean('is_active')->default(true);
            
                $table->timestamps();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
