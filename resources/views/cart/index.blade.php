<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина</title>
</head>
<body>
    <h1>Корзина</h1>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif

    @if($items->isEmpty())
        <p>Корзина пуста</p>
    @else
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>
                            @if($item->purchasable_type === 'App\\Models\\Sku')
                                {{ $item->purchasable->product->name ?? 'SKU #' . $item->purchasable_id }}
                                ({{ $item->purchasable->sku ?? '' }})
                            @else
                                {{ $item->purchasable->name ?? 'Product #' . $item->purchasable_id }}
                            @endif
                        </td>
                        <td>{{ number_format($item->price, 2) }} BYN</td>
                        <td>
                            <form action="{{ route('cart.update', $item) }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="0" style="width: 60px;">
                                <button type="submit">Обновить</button>
                            </form>
                        </td>
                        <td>{{ number_format($item->price * $item->quantity, 2) }} BYN</td>
                        <td>
                            <form action="{{ route('cart.remove', $item) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p><strong>Товаров:</strong> {{ $count }}</p>
        <p><strong>Итого:</strong> {{ number_format($total, 2) }} BYN</p>

        <form action="{{ route('cart.clear') }}" method="POST">
            @csrf
            <button type="submit">Очистить корзину</button>
        </form>

        <a href="{{ route('checkout.index') }}">Оформить заказ</a>
    @endif

    <p><a href="/">На главную</a></p>
</body>
</html>
