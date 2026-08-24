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
            $table->id();
        
            $table->string('name');
            $table->string('slug')->unique();
        
            $table->string('type')->default('select');
        
            $table->string('unit')->nullable();
        
            $table->boolean('is_filterable')->default(true);
        
            $table->boolean('is_variant')->default(false);
        
            $table->boolean('is_required')->default(false);
        
            $table->integer('sort_order')->default(0);
        
            $table->boolean('is_active')->default(true);
        
            $table->timestamps();
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
