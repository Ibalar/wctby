<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
</head>
<body>
    <h1>Личный кабинет</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    <div>
        <img src="{{ $user->avatar_url }}" alt="Аватар" width="120" height="120">
    </div>

    <p><strong>Имя:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Телефон:</strong> {{ $user->phone ?: '—' }}</p>

    <p><strong>Всего заказов:</strong> {{ $totalOrders }}</p>
    <p><strong>Потрачено:</strong> {{ number_format($totalSpent, 2, '.', ' ') }}</p>

    <h2>Последние заказы</h2>

    @if ($lastOrders->isEmpty())
        <p>Заказов пока нет.</p>
    @else
        <ul>
            @foreach ($lastOrders as $order)
                <li>
                    <a href="{{ route('profile.order', $order) }}">Заказ #{{ $order->number }}</a>
                    — {{ $order->created_at?->format('d.m.Y H:i') }}
                    — {{ $order->status }}
                    — {{ number_format($order->total, 2, '.', ' ') }}
                </li>
            @endforeach
        </ul>
    @endif

    <h2>Разделы</h2>
    <ul>
        <li><a href="{{ route('profile.edit') }}">Редактирование профиля</a></li>
        <li><a href="{{ route('profile.orders') }}">История заказов</a></li>
        <li><a href="#">Адреса</a></li>
        <li><a href="{{ route('profile.security') }}">Безопасность</a></li>
        <li><a href="{{ route('profile.social') }}">Социальные аккаунты</a></li>
    </ul>
</body>
</html>
