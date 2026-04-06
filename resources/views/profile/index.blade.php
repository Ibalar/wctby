@extends('layouts.main')

@section('title', 'Личный кабинет')

@section('content')
    @php
        $basicInfoFields = ['name', 'first_name', 'last_name', 'middle_name', 'birthday', 'gender', 'avatar'];
        $contactInfoFields = ['email', 'phone'];
        $passwordFields = ['current_password', 'password', 'password_confirmation'];

        $hasBasicErrors = $errors->hasAny($basicInfoFields);
        $hasContactErrors = $errors->hasAny($contactInfoFields);
        $hasPasswordErrors = $errors->hasAny($passwordFields);

        $genderLabels = [
            'male' => 'Мужской',
            'female' => 'Женский',
        ];

        $statusLabels = [
            'new' => ['label' => 'Новый', 'class' => 'text-bg-secondary'],
            'processing' => ['label' => 'В обработке', 'class' => 'text-bg-info'],
            'paid' => ['label' => 'Оплачен', 'class' => 'text-bg-primary'],
            'completed' => ['label' => 'Выполнен', 'class' => 'text-bg-success'],
            'cancelled' => ['label' => 'Отменён', 'class' => 'text-bg-danger'],
        ];

        $lastLoginAt = $user->last_login_at
            ? \Illuminate\Support\Carbon::parse($user->last_login_at)->format('d.m.Y H:i')
            : 'нет данных';
    @endphp

    <div class="container py-5 mt-n2 mt-sm-0">
        <div class="row pt-md-2 pt-lg-3 pb-sm-2 pb-md-3 pb-lg-4 pb-xl-5">
            <aside class="col-lg-3">
                <div class="offcanvas-lg offcanvas-start pe-lg-0 pe-xl-4" id="accountSidebar">
                    <div class="offcanvas-header d-lg-block py-3 p-lg-0">
                        <div class="d-flex align-items-center">
                            <img
                                src="{{ $user->avatar_url }}"
                                alt="{{ $user->display_name }}"
                                class="rounded-circle object-fit-cover flex-shrink-0"
                                width="48"
                                height="48"
                            >
                            <div class="min-w-0 ps-3">
                                <h5 class="h6 mb-1 text-truncate">{{ $user->full_name_middle ?: $user->name }}</h5>
                                <div class="nav flex-nowrap text-nowrap min-w-0">
                                    <span class="nav-link text-body p-0">
                                        <i class="ci-mail fs-sm opacity-75 me-2"></i>
                                        <span class="text-truncate">{{ $user->email }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-close d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#accountSidebar" aria-label="Close"></button>
                    </div>

                    <div class="offcanvas-body d-block pt-2 pt-lg-4 pb-lg-0">
                        <div class="row row-cols-3 g-3 mb-4 pb-2">
                            <div class="col">
                                <div class="border rounded-4 text-center p-3 h-100">
                                    <div class="fs-xl fw-semibold text-dark-emphasis">{{ $totalOrders }}</div>
                                    <div class="fs-xs text-body-secondary">Заказов</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border rounded-4 text-center p-3 h-100">
                                    <div class="fs-xl fw-semibold text-dark-emphasis">{{ number_format($totalSpent, 0, '.', ' ') }}</div>
                                    <div class="fs-xs text-body-secondary">Потрачено</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border rounded-4 text-center p-3 h-100">
                                    <div class="fs-xl fw-semibold text-dark-emphasis">{{ $user->is_email_verified ? 'Да' : 'Нет' }}</div>
                                    <div class="fs-xs text-body-secondary">Email</div>
                                </div>
                            </div>
                        </div>

                        <h6 class="pt-1 ps-2 ms-1">Покупки</h6>
                        <nav class="list-group list-group-borderless">
                            <a class="list-group-item list-group-item-action d-flex align-items-center" href="{{ route('profile.orders') }}">
                                <i class="ci-shopping-bag fs-base opacity-75 me-2"></i>
                                Заказы
                                <span class="badge bg-primary rounded-pill ms-auto">{{ $totalOrders }}</span>
                            </a>
                            <a class="list-group-item list-group-item-action d-flex align-items-center" href="{{ route('cart.index') }}">
                                <i class="ci-shopping-cart fs-base opacity-75 me-2"></i>
                                Корзина
                            </a>
                        </nav>

                        <h6 class="pt-4 ps-2 ms-1">Управление аккаунтом</h6>
                        <nav class="list-group list-group-borderless">
                            <a class="list-group-item list-group-item-action d-flex align-items-center active" href="{{ route('profile.index') }}">
                                <i class="ci-user fs-base opacity-75 me-2"></i>
                                Личные данные
                            </a>
                            <a class="list-group-item list-group-item-action d-flex align-items-center" href="{{ route('profile.addresses') }}">
                                <i class="ci-map-pin fs-base opacity-75 me-2"></i>
                                Адреса
                            </a>
                            <a class="list-group-item list-group-item-action d-flex align-items-center" href="{{ route('profile.social') }}">
                                <i class="ci-link fs-base opacity-75 me-2"></i>
                                Социальные аккаунты
                            </a>
                        </nav>

                        <h6 class="pt-4 ps-2 ms-1">Действия</h6>
                        <nav class="list-group list-group-borderless">
                            <a class="list-group-item list-group-item-action d-flex align-items-center" href="{{ route('catalog.index') }}">
                                <i class="ci-grid fs-base opacity-75 me-2"></i>
                                Каталог
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="list-group-item list-group-item-action d-flex align-items-center w-100 text-start border-0 bg-transparent">
                                    <i class="ci-log-out fs-base opacity-75 me-2"></i>
                                    Выйти
                                </button>
                            </form>
                        </nav>
                    </div>
                </div>
            </aside>

            <div class="col-lg-9">
                <div class="ps-lg-3 ps-xl-0">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 pb-1 pb-sm-2">
                        <div>
                            <h1 class="h2 mb-1">Личный кабинет</h1>
                            <p class="text-body-secondary mb-0">Управление профилем, контактами и безопасностью аккаунта.</p>
                        </div>
                        <a class="btn btn-outline-secondary d-lg-none" href="#accountSidebar" data-bs-toggle="offcanvas" aria-controls="accountSidebar">
                            <i class="ci-sidebar fs-base me-2"></i>
                            Меню кабинета
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4" role="alert">
                            <div class="fw-semibold mb-2">Проверьте заполнение формы.</div>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="border rounded-5 p-4 p-sm-5 mb-4">
                        <div class="row g-4 align-items-center">
                            <div class="col-md-7">
                                <div class="d-flex align-items-center gap-3">
                                    <img
                                        src="{{ $user->avatar_url }}"
                                        alt="{{ $user->display_name }}"
                                        class="rounded-circle object-fit-cover flex-shrink-0"
                                        width="88"
                                        height="88"
                                    >
                                    <div>
                                        <div class="h4 mb-1">{{ $user->full_name ?: $user->name }}</div>
                                        <div class="text-body-secondary">{{ $user->phone ?: 'Телефон не указан' }}</div>
                                        <div class="text-body-secondary">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="row row-cols-2 g-3 text-center">
                                    <div class="col">
                                        <div class="bg-body-tertiary rounded-4 p-3 h-100">
                                            <div class="fs-4 fw-semibold text-dark-emphasis">{{ $lastOrders->count() }}</div>
                                            <div class="fs-sm text-body-secondary">Последних заказов</div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="bg-body-tertiary rounded-4 p-3 h-100">
                                            <div class="fs-4 fw-semibold text-dark-emphasis">{{ $user->is_email_verified ? 'Подтверждён' : 'Не подтверждён' }}</div>
                                            <div class="fs-sm text-body-secondary">Статус email</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-bottom py-4">
                        <div class="nav flex-nowrap align-items-center justify-content-between pb-1 mb-3">
                            <h2 class="h6 mb-0">Основная информация</h2>
                            <a class="nav-link hiding-collapse-toggle text-decoration-underline p-0 {{ $hasBasicErrors ? '' : 'collapsed' }}" href=".basic-info" data-bs-toggle="collapse" aria-expanded="{{ $hasBasicErrors ? 'true' : 'false' }}" aria-controls="basicInfoPreview basicInfoEdit">Изменить</a>
                        </div>
                        <div class="collapse basic-info {{ $hasBasicErrors ? '' : 'show' }}" id="basicInfoPreview">
                            <ul class="list-unstyled fs-sm m-0">
                                <li>{{ $user->full_name_middle ?: $user->name }}</li>
                                <li>{{ $user->birthday?->translatedFormat('d F Y') ?: 'Дата рождения не указана' }}</li>
                                <li>{{ $genderLabels[$user->gender] ?? 'Пол не указан' }}</li>
                            </ul>
                        </div>
                        <div class="collapse basic-info {{ $hasBasicErrors ? 'show' : '' }}" id="basicInfoEdit">
                            <form class="row g-3 g-sm-4" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="col-sm-6">
                                    <label for="name" class="form-label">Отображаемое имя</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="col-sm-6">
                                    <label for="first_name" class="form-label">Имя</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}">
                                </div>
                                <div class="col-sm-6">
                                    <label for="last_name" class="form-label">Фамилия</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}">
                                </div>
                                <div class="col-sm-6">
                                    <label for="middle_name" class="form-label">Отчество</label>
                                    <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}">
                                </div>
                                <div class="col-sm-6">
                                    <label for="birthday" class="form-label">Дата рождения</label>
                                    <input type="date" class="form-control" id="birthday" name="birthday" value="{{ old('birthday', $user->birthday?->format('Y-m-d')) }}">
                                </div>
                                <div class="col-sm-6">
                                    <label for="gender" class="form-label">Пол</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="">Не указан</option>
                                        <option value="male" @selected(old('gender', $user->gender) === 'male')>Мужской</option>
                                        <option value="female" @selected(old('gender', $user->gender) === 'female')>Женский</option>
                                    </select>
                                </div>
                                <div class="col-sm-8">
                                    <label for="avatar" class="form-label">Аватар</label>
                                    <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                </div>
                                <div class="col-sm-4 d-flex align-items-end">
                                    @if ($user->avatar)
                                        <button type="submit" form="delete-avatar-form" class="btn btn-outline-danger w-100">Удалить аватар</button>
                                    @endif
                                </div>
                                <div class="col-12">
                                    <div class="d-flex gap-3 pt-2 pt-sm-0 flex-wrap">
                                        <button type="submit" class="btn btn-primary">Сохранить</button>
                                        <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target=".basic-info" aria-expanded="true" aria-controls="basicInfoPreview basicInfoEdit">Закрыть</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="border-bottom py-4">
                        <div class="nav flex-nowrap align-items-center justify-content-between pb-1 mb-3">
                            <h2 class="h6 mb-0">Контактные данные</h2>
                            <a class="nav-link hiding-collapse-toggle text-decoration-underline p-0 {{ $hasContactErrors ? '' : 'collapsed' }}" href=".contact-info" data-bs-toggle="collapse" aria-expanded="{{ $hasContactErrors ? 'true' : 'false' }}" aria-controls="contactInfoPreview contactInfoEdit">Изменить</a>
                        </div>
                        <div class="collapse contact-info {{ $hasContactErrors ? '' : 'show' }}" id="contactInfoPreview">
                            <ul class="list-unstyled fs-sm m-0">
                                <li>{{ $user->email }}</li>
                                <li>{{ $user->phone ?: 'Телефон не указан' }}</li>
                            </ul>
                        </div>
                        <div class="collapse contact-info {{ $hasContactErrors ? 'show' : '' }}" id="contactInfoEdit">
                            <form class="row g-3 g-sm-4" method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ old('name', $user->name) }}">
                                <input type="hidden" name="first_name" value="{{ old('first_name', $user->first_name) }}">
                                <input type="hidden" name="last_name" value="{{ old('last_name', $user->last_name) }}">
                                <input type="hidden" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}">
                                <input type="hidden" name="birthday" value="{{ old('birthday', $user->birthday?->format('Y-m-d')) }}">
                                <input type="hidden" name="gender" value="{{ old('gender', $user->gender) }}">
                                <div class="col-sm-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                </div>
                                <div class="col-sm-6">
                                    <label for="phone" class="form-label">Телефон</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                </div>
                                <div class="col-12">
                                    <div class="d-flex gap-3 pt-2 pt-sm-0 flex-wrap">
                                        <button type="submit" class="btn btn-primary">Сохранить</button>
                                        <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target=".contact-info" aria-expanded="true" aria-controls="contactInfoPreview contactInfoEdit">Закрыть</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="border-bottom py-4" id="password-section">
                        <div class="nav flex-nowrap align-items-center justify-content-between pb-1 mb-3">
                            <h2 class="h6 mb-0">Пароль</h2>
                            <a class="nav-link hiding-collapse-toggle text-decoration-underline p-0 {{ $hasPasswordErrors ? '' : 'collapsed' }}" href=".password-change" data-bs-toggle="collapse" aria-expanded="{{ $hasPasswordErrors ? 'true' : 'false' }}" aria-controls="passChangePreview passChangeEdit">Изменить</a>
                        </div>
                        <div class="collapse password-change {{ $hasPasswordErrors ? '' : 'show' }}" id="passChangePreview">
                            <ul class="list-unstyled fs-sm m-0">
                                <li>**************</li>
                                <li class="text-body-secondary">Последний вход: {{ $lastLoginAt }}</li>
                            </ul>
                        </div>
                        <div class="collapse password-change {{ $hasPasswordErrors ? 'show' : '' }}" id="passChangeEdit">
                            <form class="row g-3 g-sm-4" method="POST" action="{{ route('profile.password.update') }}">
                                @csrf
                                @method('PUT')
                                <div class="col-sm-6">
                                    <label for="current_password" class="form-label">Текущий пароль</label>
                                    <div class="password-toggle">
                                        <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Введите текущий пароль" required>
                                        <label class="password-toggle-button" aria-label="Показать или скрыть пароль">
                                            <input type="checkbox" class="btn-check">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="password" class="form-label">Новый пароль</label>
                                    <div class="password-toggle">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Введите новый пароль" required>
                                        <label class="password-toggle-button" aria-label="Показать или скрыть пароль">
                                            <input type="checkbox" class="btn-check">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                                    <div class="password-toggle">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Повторите новый пароль" required>
                                        <label class="password-toggle-button" aria-label="Показать или скрыть пароль">
                                            <input type="checkbox" class="btn-check">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex gap-3 pt-2 pt-sm-0 flex-wrap">
                                        <button type="submit" class="btn btn-primary">Сохранить</button>
                                        <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target=".password-change" aria-expanded="true" aria-controls="passChangePreview passChangeEdit">Закрыть</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="pt-4 mt-2">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 pb-1 mb-3">
                            <h2 class="h4 mb-0">Последние заказы</h2>
                            <a href="{{ route('profile.orders') }}" class="nav-link text-decoration-underline p-0">Вся история</a>
                        </div>

                        @if ($lastOrders->isEmpty())
                            <div class="border rounded-5 p-4 text-center text-body-secondary">
                                У вас пока нет оформленных заказов.
                            </div>
                        @else
                            <div class="vstack gap-3">
                                @foreach ($lastOrders as $order)
                                    @php($status = $statusLabels[$order->status] ?? ['label' => $order->status, 'class' => 'text-bg-secondary'])
                                    <div class="border rounded-5 p-4">
                                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                                            <div>
                                                <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                                                    <h3 class="h6 mb-0">Заказ #{{ $order->number }}</h3>
                                                    <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                                                </div>
                                                <div class="fs-sm text-body-secondary">
                                                    {{ $order->created_at?->format('d.m.Y H:i') }} · {{ $order->items_count ?? $order->items->count() }} шт.
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="text-md-end">
                                                    <div class="fs-sm text-body-secondary">Сумма</div>
                                                    <div class="h6 mb-0">{{ number_format($order->total, 2, '.', ' ') }}</div>
                                                </div>
                                                <a href="{{ route('profile.order', $order) }}" class="btn btn-outline-secondary">Подробнее</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="fixed-bottom z-sticky w-100 btn btn-lg btn-dark border-0 border-top border-light border-opacity-10 rounded-0 pb-4 d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#accountSidebar" aria-controls="accountSidebar" data-bs-theme="light">
        <i class="ci-sidebar fs-base me-2"></i>
        Меню кабинета
    </button>

    @if ($user->avatar)
        <form id="delete-avatar-form" method="POST" action="{{ route('profile.avatar.delete') }}" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endif
@endsection
