<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа</title>
</head>
<body>
    <h1>Оформление заказа</h1>

    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($items->isEmpty())
        <p>Корзина пуста. <a href="{{ route('catalog.index') }}">Перейти в каталог</a></p>
    @else
        <h2>Товары в заказе</h2>
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
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
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price * $item->quantity, 2) }} BYN</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p><strong>Итого по товарам:</strong> {{ number_format($total, 2) }} BYN</p>

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf

            <h2>Контактные данные</h2>
            <p>
                <label for="customer_name">Имя</label><br>
                <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
            </p>
            <p>
                <label for="customer_phone">Телефон</label><br>
                <input type="text" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required>
            </p>
            <p>
                <label for="customer_email">Email</label><br>
                <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}">
            </p>

            <h2>Способы доставки и оплаты</h2>
            <p>
                <label for="delivery_method">Способ доставки</label><br>
                <select id="delivery_method" name="delivery_method" required>
                    <option value="">Выберите способ доставки</option>
                    @foreach($deliveryMethods as $method)
                        <option value="{{ $method->id }}" @selected(old('delivery_method') == $method->id)>
                            {{ $method->name }} ({{ number_format($method->price, 2) }} BYN)
                        </option>
                    @endforeach
                </select>
            </p>

            <p>
                <label for="payment_method">Способ оплаты</label><br>
                <select id="payment_method" name="payment_method" required>
                    <option value="">Выберите способ оплаты</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->id }}" @selected(old('payment_method') == $method->id)>
                            {{ $method->name }}
                        </option>
                    @endforeach
                </select>
            </p>

            <h2>Адрес доставки</h2>
            <p>
                <label for="city">Город</label><br>
                <input type="text" id="city" name="city" value="{{ old('city') }}" required>
            </p>
            <p>
                <label for="street">Улица</label><br>
                <input type="text" id="street" name="street" value="{{ old('street') }}" required>
            </p>
            <p>
                <label for="house">Дом</label><br>
                <input type="text" id="house" name="house" value="{{ old('house') }}" required>
            </p>
            <p>
                <label for="apartment">Квартира</label><br>
                <input type="text" id="apartment" name="apartment" value="{{ old('apartment') }}">
            </p>

            <button type="submit">Подтвердить заказ</button>
        </form>
    @endif

    <p><a href="{{ route('cart.index') }}">Вернуться в корзину</a></p>
</body>
</html>
