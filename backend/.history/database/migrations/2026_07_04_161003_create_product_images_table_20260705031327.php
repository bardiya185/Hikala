Schema::create('product_images', function (Blueprint $table) {
    $table->id();

    $table->foreignId('product_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('image_path');

    $table->string('alt')->nullable();

    $table->unsignedInteger('sort_order')->default(1);

    $table->boolean('is_main')->default(false);

    $table->timestamps();

    $table->index(['product_id', 'sort_order'], 'product_images_sort_idx');
});