Schema::create('discount_product_variant', function (Blueprint $table) {

$table->id();

$table->foreignId('discount_id')
    ->constrained()
    ->cascadeOnDelete();

$table->foreignId('product_variant_id')
    ->constrained()
    ->cascadeOnDelete();

$table->timestamps();

$table->unique([
    'discount_id',
    'product_variant_id'
]);
});