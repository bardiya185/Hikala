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
        Schema::create('addresses', function (Blueprint $table) {

            $table->id();
        
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('title');
            $table->string('receiver_name');
        
            $table->string('receiver_mobile',11);
            $table->foreignId('province_id')
                ->constrained()
                ->restrictOnDelete();
        
            $table->foreignId('city_id')
                ->constrained()
                ->restrictOnDelete();
            $table->text('address');
            $table->string('building_number')->nullable();
            $table->string('unit')->nullable();
            $table->string('postal_code',10)->nullable();
            $table->decimal('latitude',10,7)->nullable();
        
            $table->decimal('longitude',10,7)->nullable();
            $table->boolean('is_default')
                ->default(false);
        
            $table->timestamps();

            $table->index(['user_id', 'is_default']);


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
