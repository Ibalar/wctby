<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заказ оформлен</title>
</head>
<body>
    <h1>Спасибо! Заказ успешно оформлен</h1>

    <p><strong>Номер заказа:</strong> {{ $order->number }}</p>
    <p><strong>Сумма заказа:</strong> {{ number_format($order->total, 2) }} {{ $order->currency }}</p>

    <h2>Способы оплаты и доставки</h2>
    <p><strong>Оплата:</strong> {{ $order->payment_method_name ?? $order->payment_method }}</p>
    <p><strong>Доставка:</strong> {{ $order->delivery_method_name ?? $order->delivery_method }}</p>

    <h2>Контактные данные</h2>
    <p><strong>Имя:</strong> {{ $order->customer_name }}</p>
    <p><strong>Телефон:</strong> {{ $order->customer_phone }}</p>
    <p><strong>Email:</strong> {{ $order->customer_email ?: '—' }}</p>

    <h2>Адрес доставки</h2>
    <p>
        {{ $order->shipping_address['city'] ?? '' }},
        {{ $order->shipping_address['street'] ?? '' }},
        дом {{ $order->shipping_address['house'] ?? '' }}
        @if(!empty($order->shipping_address['apartment']))
            , кв. {{ $order->shipping_address['apartment'] }}
        @endif
    </p>

    <p><a href="{{ route('catalog.index') }}">Продолжить покупки</a></p>
</body>
</html>
