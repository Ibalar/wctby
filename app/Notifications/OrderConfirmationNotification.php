<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmationNotification extends Notification implements ShouldQueue
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
            ->subject('Заказ #' . $this->order->number . ' оформлен')
            ->greeting('Здравствуйте, ' . $this->order->customer_name . '!')
            ->line('Ваш заказ #' . $this->order->number . ' успешно оформлен.')
            ->line('Сумма заказа: ' . number_format($this->order->total, 2) . ' ' . $this->order->currency)
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

        $mail->line('Товары в заказе:')
            ->line('');

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
            ->action('Просмотреть заказ', route('checkout.success', ['orderNumber' => $this->order->number]))
            ->line('Спасибо за покупку!');

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->number,
            'total' => $this->order->total,
            'currency' => $this->order->currency,
            'message' => 'Заказ #' . $this->order->number . ' успешно оформлен',
        ];
    }
}
