<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заказ #{{ $order->number }}</title>
</head>
<body>
    <h1>Заказ #{{ $order->number }}</h1>

    <p><strong>Статус:</strong> {{ $order->status }}</p>
    <p><strong>Дата:</strong> {{ $order->created_at?->format('d.m.Y H:i') }}</p>

    <h2>Товары</h2>
    @if ($order->items->isEmpty())
        <p>Нет товаров в заказе.</p>
    @else
        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ number_format($item->price, 2, '.', ' ') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->total, 2, '.', ' ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2>Суммы</h2>
    <p><strong>Подитог:</strong> {{ number_format($order->subtotal, 2, '.', ' ') }}</p>
    <p><strong>Доставка:</strong> {{ number_format($order->shipping_amount ?? $order->delivery_price ?? 0, 2, '.', ' ') }}</p>
    <p><strong>Итого:</strong> {{ number_format($order->total, 2, '.', ' ') }}</p>

    <h2>Данные клиента</h2>
    <p><strong>ФИО:</strong> {{ $order->customer_name }}</p>
    <p><strong>Телефон:</strong> {{ $order->customer_phone }}</p>
    <p><strong>Email:</strong> {{ $order->customer_email }}</p>

    <h2>Адрес доставки</h2>
    @php($address = $order->shipping_address)
    @if (is_array($address))
        <ul>
            @foreach ($address as $key => $value)
                <li><strong>{{ $key }}:</strong> {{ is_scalar($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE) }}</li>
            @endforeach
        </ul>
    @else
        <p>{{ $address ?: 'Не указан' }}</p>
    @endif

    <p><a href="{{ route('profile.orders') }}">Назад к заказам</a></p>
</body>
</html>
