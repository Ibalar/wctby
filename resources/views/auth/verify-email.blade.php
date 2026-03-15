<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подтверждение email</title>
</head>
<body>
    <h1>Подтвердите ваш email</h1>

    <p>Пожалуйста, подтвердите адрес электронной почты, перейдя по ссылке из письма.</p>

    @if (session('status') === 'verification-link-sent')
        <p>Новая ссылка для подтверждения отправлена на ваш email.</p>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">Отправить письмо повторно</button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Выйти</button>
    </form>
</body>
</html>
