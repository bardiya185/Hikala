<?php

use App\Enums\OrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_status_history', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->cascadeOnDelete();
            $table->enum('from_status', OrderStatus::values())
                  ->nullable();  // null for first entry
            
            $table->enum('to_status', OrderStatus::values());
            $table->foreignId('changed_by_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index('order_id');
            $table->index('to_status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_history');
    }
};