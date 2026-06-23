<?php

namespace App\Notifications;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class OrderStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Order $order,
        protected OrderStatus $oldStatus,
        protected OrderStatus $newStatus,
    ) {
        Log::debug('[OrderStatusChangedNotification] Created', [
            'order_id' => $order->id,
            'old' => $oldStatus->value,
            'new' => $newStatus->value,
        ]);
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        Log::info('[OrderStatusChangedNotification] Sending email', [
            'order_id' => $this->order->id,
            'email' => $this->order->customer_email,
        ]);

        return (new MailMessage)
            ->subject('Статус заказа #' . $this->order->number . ' изменён')
            ->greeting('Здравствуйте, ' . $this->order->customer_name . '!')
            ->line('Статус вашего заказа #' . $this->order->number . ' изменился.')
            ->line('')
            ->line($this->oldStatus->label() . ' → ' . $this->newStatus->label())
            ->line('')
            ->action('Просмотреть заказ', route('checkout.success', ['orderNumber' => $this->order->number]))
            ->line('Спасибо, что выбрали наш магазин!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->number,
            'old_status' => $this->oldStatus->value,
            'new_status' => $this->newStatus->value,
            'message' => 'Статус заказа #' . $this->order->number
                . ' изменён: ' . $this->oldStatus->label()
                . ' → ' . $this->newStatus->label(),
        ];
    }
}
