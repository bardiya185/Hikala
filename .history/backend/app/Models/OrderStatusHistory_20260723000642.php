<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusHistory extends Model
{
    protected $table = 'order_status_history';
    
    // ⚠️ فقط created_at داره، updated_at نداره
    public $timestamps = false;
    
    protected $fillable = [
        'order_id',
        'from_status',
        'to_status',
        'changed_by_user_id',
        'note',
        'created_at',
    ];

    protected $casts = [
        'from_status' => OrderStatus::class,
        'to_status' => OrderStatus::class,
        'created_at' => 'datetime',
    ];

    // ================================================================
    // 🔗 Relationships
    // ================================================================

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }

    // ================================================================
    // 🎯 Accessors
    // ================================================================

    public function getFromLabelAttribute(): ?string
    {
        return $this->from_status?->label();
    }

    public function getToLabelAttribute(): string
    {
        return $this->to_status->label();
    }
}