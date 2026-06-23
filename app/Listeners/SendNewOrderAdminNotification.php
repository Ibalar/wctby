<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Notifications\NewOrderAdminNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendNewOrderAdminNotification
{
    public function handle(OrderCreated $event): void
    {
        $adminEmail = config('mail.admin_email');

        if (!$adminEmail) {
            return;
        }

        $event->order->load('items');
        Notification::route('mail', $adminEmail)
            ->notify(new NewOrderAdminNotification($event->order));

        Log::info('[SendNewOrderAdminNotification] Sent', [
            'order_id' => $event->order->id,
            'admin_email' => $adminEmail,
        ]);
    }
}
