<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>История заказов</title>
</head>
<body>
    <h1>История заказов</h1>

    <form method="GET" action="{{ route('profile.orders') }}">
        <label for="status">Статус</label>
        <select id="status" name="status">
            <option value="">Все</option>
            @foreach (['new', 'processing', 'paid', 'completed', 'cancelled'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
            @endforeach
        </select>
        <button type="submit">Фильтр</button>
    </form>

    @if ($orders->isEmpty())
        <p>Заказов не найдено.</p>
    @else
        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>Номер</th>
                    <th>Дата</th>
                    <th>Статус</th>
                    <th>Сумма</th>
                    <th>Товаров</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>#{{ $order->number }}</td>
                        <td>{{ $order->created_at?->format('d.m.Y H:i') }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ number_format($order->total, 2, '.', ' ') }}</td>
                        <td>{{ $order->items->count() }}</td>
                        <td><a href="{{ route('profile.order', $order) }}">Детали</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $orders->withQueryString()->links() }}
    @endif

    <p><a href="{{ route('profile.index') }}">Назад в личный кабинет</a></p>
</body>
</html>
