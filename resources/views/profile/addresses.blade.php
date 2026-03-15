<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои адреса</title>
</head>
<body>
    <h1>Мои адреса</h1>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif

    <p><a href="{{ route('profile.index') }}">← Назад в профиль</a></p>

    @if($addresses->isEmpty())
        <p>У вас пока нет сохранённых адресов.</p>
    @else
        @foreach($addresses as $address)
            <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 10px;">
                @if($address->is_default)
                    <span style="background: #4CAF50; color: white; padding: 2px 8px; font-size: 12px;">Основной</span>
                @endif

                <p>
                    <strong>Город:</strong> {{ $address->city }}<br>
                    <strong>Улица:</strong> {{ $address->street }}
                    @if($address->house)
                        , д. {{ $address->house }}
                    @endif
                    @if($address->apartment)
                        , кв. {{ $address->apartment }}
                    @endif
                    @if($address->postal_code)
                        <br><strong>Индекс:</strong> {{ $address->postal_code }}
                    @endif
                </p>

                <p>
                    @if(!$address->is_default)
                        <form action="{{ route('profile.addresses.default', $address) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit">Сделать основным</button>
                        </form>
                    @endif

                    <form action="{{ route('profile.addresses.destroy', $address) }}" method="POST" style="display:inline;" onsubmit="return confirm('Удалить адрес?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="color: red;">Удалить</button>
                    </form>
                </p>
            </div>
        @endforeach
    @endif

    <h2>Добавить новый адрес</h2>

    <form action="{{ route('profile.addresses.store') }}" method="POST">
        @csrf

        <p>
            <label>Тип адреса:</label><br>
            <select name="type">
                <option value="shipping">Для доставки</option>
                <option value="billing">Для счетов</option>
            </select>
        </p>

        <p>
            <label>Город: *</label><br>
            <input type="text" name="city" value="{{ old('city') }}" required>
        </p>

        <p>
            <label>Улица: *</label><br>
            <input type="text" name="street" value="{{ old('street') }}" required>
        </p>

        <p>
            <label>Дом:</label><br>
            <input type="text" name="house" value="{{ old('house') }}">
        </p>

        <p>
            <label>Квартира:</label><br>
            <input type="text" name="apartment" value="{{ old('apartment') }}">
        </p>

        <p>
            <label>Почтовый индекс:</label><br>
            <input type="text" name="postal_code" value="{{ old('postal_code') }}">
        </p>

        <p>
            <label>
                <input type="checkbox" name="is_default" value="1">
                Сделать основным адресом
            </label>
        </p>

        <p>
            <button type="submit">Добавить адрес</button>
        </p>
    </form>
</body>
</html>
