<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variant_shipping_features', function (Blueprint $table) {

            $table->id();

            $table->foreignId('product_variant_id')
                ->constrained()
                ->cascadeOnDelete();


            $table->enum('type', [
                'fast',
                'same_day',
                'free',
                'standard',
            ]);


            $table->string('title');


            $table->text('description')
                ->nullable();


            $table->boolean('is_active')
                ->default(true);


            $table->timestamps();


            $table->index([
                'product_variant_id',
                'type'
            ]);

        });
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'product_variant_shipping_features'
        );
    }
};
