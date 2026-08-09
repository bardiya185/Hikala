<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {

            $table->id();
       
            $table->string('name');
 
            $table->enum('type', [
                'percent',
                'fixed',
            ]);

            $table->boolean('stackable')->default(0);
   
            $table->decimal('value', 12, 2);
      
            $table->timestamp('starts_at')->nullable();

            $table->timestamp('ends_at')->nullable();

            $table->unsignedInteger('quantity_limit')->nullable();

            $table->unsignedInteger('used_quantity')
            ->default(0);

            $table->unsignedInteger('priority')->default(0);
          
            $table->foreignId('campaign_id')
            ->nullable()
            ->constrained('discount_campaigns')
            ->nullOnDelete();
      
           
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('starts_at');
            $table->index('ends_at');
            $table->index('campaign_id');
            $table->index('is_active');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};