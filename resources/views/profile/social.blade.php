<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Социальные аккаунты</title>
</head>
<body>
    <h1>Социальные аккаунты</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    <ul>
        @foreach ($providers as $provider)
            @php($linked = in_array($provider, $linkedProviders, true))
            <li>
                <strong>{{ ucfirst($provider) }}</strong> — {{ $linked ? 'привязан' : 'не привязан' }}

                @if ($linked)
                    <form method="POST" action="{{ route('profile.social.unlink', $provider) }}" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Отвязать</button>
                    </form>
                @else
                    <a href="{{ route('social.redirect', $provider) }}">Привязать</a>
                @endif
            </li>
        @endforeach
    </ul>

    <p><a href="{{ route('profile.index') }}">Назад в личный кабинет</a></p>
</body>
</html>
