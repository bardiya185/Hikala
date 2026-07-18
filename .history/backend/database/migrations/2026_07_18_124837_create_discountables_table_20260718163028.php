<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts_table', function (Blueprint $table) {

            $table->id();

            $table->foreignId('discount_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->morphs('discounts_table');

            $table->timestamps();

            $table->unique([
                'discount_id',
                'discounts_table',
                'discounts_table',
            ]);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts_table');
    }
};