<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
</head>
<body>
    <h1>Регистрация</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name">Логин / Имя</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
        </div>

        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="first_name">Имя</label>
            <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}">
        </div>

        <div>
            <label for="last_name">Фамилия</label>
            <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}">
        </div>

        <div>
            <label for="phone">Телефон</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone') }}">
        </div>

        <div>
            <label for="password">Пароль</label>
            <input id="password" type="password" name="password" required>
        </div>

        <div>
            <label for="password_confirmation">Подтверждение пароля</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>

        <button type="submit">Зарегистрироваться</button>
    </form>

    <p><a href="{{ route('login') }}">Уже есть аккаунт? Войти</a></p>
</body>
</html>
