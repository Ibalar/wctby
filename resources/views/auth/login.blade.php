<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
</head>
<body>
    <h1>Вход</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div>
            <label for="password">Пароль</label>
            <input id="password" type="password" name="password" required>
        </div>

        <div>
            <label for="remember">
                <input id="remember" type="checkbox" name="remember">
                Запомнить меня
            </label>
        </div>

        <button type="submit">Войти</button>
    </form>

    <p><a href="{{ route('register') }}">Регистрация</a></p>
    <p><a href="{{ route('password.request') }}">Забыли пароль?</a></p>
</body>
</html>
