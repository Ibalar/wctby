<?php

namespace App\Models;

use App\Enums\OrderStatus;
use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'number', 'status', 'currency', 'subtotal', 'discount_amount',
        'shipping_amount', 'total', 'payment_method', 'delivery_method',
        'payment_method_code', 'payment_method_name',
        'delivery_method_code', 'delivery_method_name', 'delivery_price',
        'customer_name', 'customer_phone', 'customer_email', 'shipping_address',
        'status_history',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'shipping_amount' => 'float',
        'total' => 'float',
        'delivery_price' => 'float',
        'shipping_address' => 'array',
        'status' => OrderStatus::class,
        'status_history' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, [OrderStatus::New, OrderStatus::Processing], true);
    }

    public function canTransitionTo(OrderStatus $target): bool
    {
        return $this->status->canTransitionTo($target);
    }

    public function transitionTo(OrderStatus $newStatus, ?int $userId = null): void
    {
        $oldStatus = $this->status;

        Log::debug('[Order.transition] Attempting transition', [
            'order_id' => $this->id,
            'from' => $oldStatus->value,
            'to' => $newStatus->value,
        ]);

        if (!$this->canTransitionTo($newStatus)) {
            Log::error('[Order.transition] Invalid transition', [
                'order_id' => $this->id,
                'from' => $oldStatus->value,
                'to' => $newStatus->value,
            ]);
            throw new DomainException(
                "Недопустимый переход статуса: {$oldStatus->label()} → {$newStatus->label()}"
            );
        }

        $this->recordStatusChange($newStatus, $userId);
        $this->status = $newStatus;

        Log::info('[Order.transition] Status changed', [
            'order_id' => $this->id,
            'from' => $oldStatus->value,
            'to' => $newStatus->value,
            'user_id' => $userId,
        ]);
    }

    public function recordStatusChange(OrderStatus $newStatus, ?int $userId = null): void
    {
        $history = $this->status_history ?? [];

        $history[] = [
            'from' => $this->status->value,
            'to' => $newStatus->value,
            'user_id' => $userId,
            'changed_at' => now()->toIso8601String(),
        ];

        $this->status_history = $history;
    }

    public function scopeByStatus($query, string|OrderStatus $status)
    {
        $value = $status instanceof OrderStatus ? $status->value : $status;

        return $query->where('status', $value);
    }
}
