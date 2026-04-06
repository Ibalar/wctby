@extends('layouts.main')

@section('title', 'Социальные аккаунты')

@section('content')
    @php
        $providerMeta = [
            'google' => [
                'label' => 'Google',
                'icon' => 'ci-google',
                'color' => 'text-danger',
                'description' => 'Быстрый вход через аккаунт Google.',
            ],
            'vkontakte' => [
                'label' => 'VK',
                'icon' => 'ci-vk',
                'color' => 'text-info',
                'description' => 'Авторизация через профиль ВКонтакте.',
            ],
            'telegram' => [
                'label' => 'Telegram',
                'icon' => 'ci-telegram',
                'color' => 'text-primary',
                'description' => 'Вход через Telegram-аккаунт.',
            ],
            'yandex' => [
                'label' => 'Yandex',
                'icon' => 'ci-globe',
                'color' => 'text-warning',
                'description' => 'Использование Яндекс ID для входа.',
            ],
        ];
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
                                    <div class="fs-xl fw-semibold text-dark-emphasis">{{ count($linkedProviders) }}</div>
                                    <div class="fs-xs text-body-secondary">Связок</div>
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
                            <a class="list-group-item list-group-item-action d-flex align-items-center" href="{{ route('profile.index') }}">
                                <i class="ci-user fs-base opacity-75 me-2"></i>
                                Личные данные
                            </a>
                            <a class="list-group-item list-group-item-action d-flex align-items-center" href="{{ route('profile.addresses') }}">
                                <i class="ci-map-pin fs-base opacity-75 me-2"></i>
                                Адреса
                            </a>
                            <a class="list-group-item list-group-item-action d-flex align-items-center active" href="{{ route('profile.social') }}">
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
                            <h1 class="h2 mb-1">Социальные аккаунты</h1>
                            <p class="text-body-secondary mb-0">Управляйте способами входа и привязывайте внешние сервисы к вашему профилю.</p>
                        </div>
                        <a class="btn btn-outline-secondary d-lg-none" href="#accountSidebar" data-bs-toggle="offcanvas" aria-controls="accountSidebar">
                            <i class="ci-sidebar fs-base me-2"></i>
                            Меню кабинета
                        </a>
                    </div>

                    <div class="border rounded-5 p-4 p-sm-5 mb-4">
                        <div class="row g-4 align-items-center">
                            <div class="col-md-8">
                                <h2 class="h4 mb-2">Безопасный вход без лишних действий</h2>
                                <p class="text-body-secondary mb-0">Подключите удобные сервисы для быстрого входа. Если аккаунт используется как единственный способ авторизации, отвязать его нельзя, пока не задан пароль.</p>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-body-tertiary rounded-4 p-4 text-center">
                                    <div class="fs-1 fw-semibold text-dark-emphasis">{{ count($linkedProviders) }}</div>
                                    <div class="text-body-secondary">Подключённых сервисов</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="vstack gap-3">
                        @foreach ($providers as $provider)
                            @php
                                $meta = $providerMeta[$provider] ?? [
                                    'label' => ucfirst($provider),
                                    'icon' => 'ci-link',
                                    'color' => 'text-primary',
                                    'description' => 'Подключение внешнего аккаунта.',
                                ];
                                $linked = in_array($provider, $linkedProviders, true);
                            @endphp

                            <div class="border rounded-5 p-4">
                                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-body-tertiary {{ $meta['color'] }}" style="width: 3.5rem; height: 3.5rem;">
                                            <i class="{{ $meta['icon'] }} fs-3"></i>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                                <h2 class="h5 mb-0">{{ $meta['label'] }}</h2>
                                                <span class="badge {{ $linked ? 'text-bg-success' : 'text-bg-secondary' }}">
                                                    {{ $linked ? 'Подключён' : 'Не подключён' }}
                                                </span>
                                            </div>
                                            <p class="text-body-secondary mb-0">{{ $meta['description'] }}</p>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 flex-wrap">
                                        @if ($linked)
                                            <form method="POST" action="{{ route('profile.social.unlink', $provider) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">Отвязать</button>
                                            </form>
                                        @else
                                            <a href="{{ route('social.redirect', $provider) }}" class="btn btn-primary">Привязать</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-top pt-4 mt-4">
                        <h2 class="h5 mb-2">Что это даёт</h2>
                        <ul class="list-unstyled text-body-secondary mb-0">
                            <li class="mb-2">Быстрый вход без ручного ввода пароля.</li>
                            <li class="mb-2">Дополнительный резервный способ авторизации.</li>
                            <li>Гибкое управление доступом к аккаунту из личного кабинета.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="fixed-bottom z-sticky w-100 btn btn-lg btn-dark border-0 border-top border-light border-opacity-10 rounded-0 pb-4 d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#accountSidebar" aria-controls="accountSidebar" data-bs-theme="light">
        <i class="ci-sidebar fs-base me-2"></i>
        Меню кабинета
    </button>
@endsection
