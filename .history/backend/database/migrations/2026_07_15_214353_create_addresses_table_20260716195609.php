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
        
            // عنوان آدرس
            $table->string('title');
        
            // گیرنده
            $table->string('receiver_name');
        
            $table->string('receiver_mobile',11);
        
            // استان و شهر
            $table->foreignId('province_id')
                ->constrained()
                ->restrictOnDelete();
        
            $table->foreignId('city_id')
                ->constrained()
                ->restrictOnDelete();
        
            // آدرس متنی
            $table->text('address');
        
            // پلاک
            $table->string('building_number')->nullable();
        
            // واحد
            $table->string('unit')->nullable();
        
            // کد پستی
            $table->string('postal_code',10)->nullable();
        
            // مختصات GPS
            $table->decimal('latitude',10,7)->nullable();
        
            $table->decimal('longitude',10,7)->nullable();
        
            // پیشفرض
            $table->boolean('is_default')
                ->default(false);
        
            $table->timestamps();
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
