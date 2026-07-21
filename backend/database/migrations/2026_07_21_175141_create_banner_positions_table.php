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
        Schema::create('banner_positions', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();      // مثل: home_middle, home_slider
            $table->string('name');                // نام قابل نمایش در پنل
            $table->string('description')->nullable();
            $table->unsignedInteger('max_banners')->default(4); // حداکثر تعداد بنر
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_positions');
    }
};
