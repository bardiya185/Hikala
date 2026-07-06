Schema::create('category_product', function (Blueprint $table) {

$table->id();

$table->foreignId('category_id')
    ->constrained()
    ->cascadeOnDelete();

$table->foreignId('product_id')
    ->constrained()
    ->cascadeOnDelete();

$table->timestamps();

$table->unique([
    'category_id',
    'product_id',
]);
});