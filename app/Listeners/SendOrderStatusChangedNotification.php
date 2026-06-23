<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendOrderStatusChangedNotification
{
    public function handle(OrderStatusChanged $event): void
    {
        $order = $event->order;

        if ($order->user_id && $order->user) {
            $order->user->notify(
                new OrderStatusChangedNotification($order, $event->oldStatus, $event->newStatus)
            );
        } elseif ($order->customer_email) {
            Notification::route('mail', $order->customer_email)
                ->notify(
                    new OrderStatusChangedNotification($order, $event->oldStatus, $event->newStatus)
                );
        }

        Log::info('[SendOrderStatusChangedNotification] Sent', [
            'order_id' => $order->id,
            'old' => $event->oldStatus->value,
            'new' => $event->newStatus->value,
        ]);
    }
}
