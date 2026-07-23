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
            
            // 📦 Which order
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->cascadeOnDelete();
            
            // 📊 Status change
            $table->enum('from_status', OrderStatus::values())
                  ->nullable();  // null for first entry
            
            $table->enum('to_status', OrderStatus::values());
            
            // 👤 Who made this change (admin or system or user)
            $table->foreignId('changed_by_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            
            // 📝 Optional note (e.g., "Package shipped via Post")
            $table->text('note')->nullable();
            
            // ⏰ When it happened
            $table->timestamp('created_at')->useCurrent();
            
            
            // 🔍 Indexes
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