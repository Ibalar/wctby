<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование профиля</title>
</head>
<body>
    <h1>Редактирование профиля</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
            <label for="name">Имя</label>
            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div>
            <label for="first_name">Имя (отдельно)</label>
            <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}">
        </div>

        <div>
            <label for="last_name">Фамилия</label>
            <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}">
        </div>

        <div>
            <label for="middle_name">Отчество</label>
            <input id="middle_name" type="text" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}">
        </div>

        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div>
            <label for="phone">Телефон</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}">
        </div>

        <div>
            <label for="birthday">Дата рождения</label>
            <input id="birthday" type="date" name="birthday" value="{{ old('birthday', $user->birthday?->format('Y-m-d')) }}">
        </div>

        <div>
            <label for="gender">Пол</label>
            <select id="gender" name="gender">
                <option value="">Не указан</option>
                <option value="male" @selected(old('gender', $user->gender) === 'male')>Мужской</option>
                <option value="female" @selected(old('gender', $user->gender) === 'female')>Женский</option>
            </select>
        </div>

        <div>
            <p>Текущий аватар:</p>
            <img src="{{ $user->avatar_url }}" alt="Аватар" width="120" height="120">
        </div>

        <div>
            <label for="avatar">Новый аватар</label>
            <input id="avatar" type="file" name="avatar" accept="image/*">
        </div>

        <button type="submit">Сохранить</button>
    </form>

    @if ($user->avatar)
        <form method="POST" action="{{ route('profile.avatar.delete') }}">
            @csrf
            @method('DELETE')
            <button type="submit">Удалить аватар</button>
        </form>
    @endif

    <p><a href="{{ route('profile.index') }}">Назад в личный кабинет</a></p>
</body>
</html>
