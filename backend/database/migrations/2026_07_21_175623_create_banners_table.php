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
    Schema::create('banners', function (Blueprint $table) {
        $table->id();
        $table->foreignId('banner_position_id')
            ->constrained('banner_positions')
            ->cascadeOnDelete();
        $table->string('title')->nullable();
        $table->string('subtitle')->nullable();
        $table->string('image');
        $table->string('mobile_image')->nullable();
        $table->string('alt_text')->nullable();
        $table->nullableMorphs('linkable'); // این دو ستون رو می‌سازه: linkable_type, linkable_id
        $table->string('custom_url')->nullable();
        $table->string('background_color', 7)->nullable();
        $table->string('text_color', 7)->nullable();
        $table->timestamp('starts_at')->nullable();
        $table->timestamp('ends_at')->nullable();
        $table->unsignedBigInteger('click_count')->default(0);
        $table->unsignedBigInteger('view_count')->default(0);
        $table->unsignedInteger('sort_order')->default(0);
        $table->boolean('is_active')->default(true);
        
        $table->timestamps();
        
        $table->index(['banner_position_id', 'is_active', 'sort_order']);
        $table->index(['starts_at', 'ends_at']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
