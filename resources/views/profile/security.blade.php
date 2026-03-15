<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Безопасность</title>
</head>
<body>
    <h1>Безопасность</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <h2>Смена пароля</h2>
    <form method="POST" action="{{ route('profile.password.update') }}">
        @csrf
        @method('PUT')

        <div>
            <label for="current_password">Текущий пароль</label>
            <input id="current_password" type="password" name="current_password" required>
        </div>

        <div>
            <label for="password">Новый пароль</label>
            <input id="password" type="password" name="password" required>
        </div>

        <div>
            <label for="password_confirmation">Подтверждение пароля</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>

        <button type="submit">Изменить пароль</button>
    </form>

    <h2>Последний вход</h2>
    <p>{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('d.m.Y H:i') : 'Нет данных' }}</p>

    <p><a href="{{ route('profile.index') }}">Назад в личный кабинет</a></p>
</body>
</html>
