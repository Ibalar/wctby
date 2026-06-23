<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Notifications\OrderConfirmationNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendOrderConfirmation
{
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;
        $order->load('items');

        if ($order->user_id && $order->user) {
            $order->user->notify(new OrderConfirmationNotification($order));

            Log::info('[SendOrderConfirmation] Sent to user', [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
            ]);
        } elseif ($order->customer_email) {
            Notification::route('mail', $order->customer_email)
                ->notify(new OrderConfirmationNotification($order));

            Log::info('[SendOrderConfirmation] Sent to email', [
                'order_id' => $order->id,
                'email' => $order->customer_email,
            ]);
        }
    }
}
