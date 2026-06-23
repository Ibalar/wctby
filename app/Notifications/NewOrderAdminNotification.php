<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderAdminNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Order $order
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Новый заказ #' . $this->order->number)
            ->greeting('Новый заказ!')
            ->line('Поступил новый заказ #' . $this->order->number)
            ->line('')
            ->line('**Информация о клиенте:**')
            ->line('Имя: ' . $this->order->customer_name)
            ->line('Телефон: ' . $this->order->customer_phone);

        if ($this->order->customer_email) {
            $mail->line('Email: ' . $this->order->customer_email);
        }

        $mail->line('')
            ->line('**Детали заказа:**')
            ->line('Сумма: ' . number_format($this->order->total, 2) . ' ' . $this->order->currency)
            ->line('Статус: ' . $this->order->status)
            ->line('Способ доставки: ' . $this->order->delivery_method_name)
            ->line('Способ оплаты: ' . $this->order->payment_method_name);

        if ($this->order->shipping_address) {
            $address = $this->order->shipping_address;
            $addressString = implode(', ', array_filter([
                $address['city'] ?? null,
                $address['street'] ?? null,
                isset($address['house']) ? 'д. ' . $address['house'] : null,
                isset($address['apartment']) ? 'кв. ' . $address['apartment'] : null,
            ]));
            $mail->line('Адрес доставки: ' . $addressString);
        }

        $mail->line('')
            ->line('**Товары:**');

        foreach ($this->order->items as $item) {
            $mail->line(sprintf(
                '• %s — %d шт. × %s = %s',
                $item->name,
                $item->quantity,
                number_format($item->price, 2) . ' ' . $this->order->currency,
                number_format($item->line_total, 2) . ' ' . $this->order->currency
            ));
        }

        $mail->line('')
            ->line('**Итого:** ' . number_format($this->order->total, 2) . ' ' . $this->order->currency)
            ->action('Перейти к заказу', url('/admin/orders/' . $this->order->id));

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->number,
            'customer_name' => $this->order->customer_name,
            'customer_phone' => $this->order->customer_phone,
            'total' => $this->order->total,
            'currency' => $this->order->currency,
            'message' => 'Новый заказ #' . $this->order->number . ' от ' . $this->order->customer_name,
        ];
    }
}
