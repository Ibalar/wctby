@extends('layouts.main')

@section('title', 'История заказов')

@section('content')
    @php
        $statusTabs = [
            '' => ['label' => 'Все', 'badge' => $totalOrders],
            'new' => ['label' => 'Новые', 'badge' => (int) ($statusCounts['new'] ?? 0)],
            'processing' => ['label' => 'В обработке', 'badge' => (int) ($statusCounts['processing'] ?? 0)],
            'paid' => ['label' => 'Оплаченные', 'badge' => (int) ($statusCounts['paid'] ?? 0)],
            'completed' => ['label' => 'Выполненные', 'badge' => (int) ($statusCounts['completed'] ?? 0)],
            'cancelled' => ['label' => 'Отменённые', 'badge' => (int) ($statusCounts['cancelled'] ?? 0)],
        ];

        $statusBadges = [
            'new' => ['label' => 'Новый', 'class' => 'text-bg-secondary'],
            'processing' => ['label' => 'В обработке', 'class' => 'text-bg-info'],
            'paid' => ['label' => 'Оплачен', 'class' => 'text-bg-primary'],
            'completed' => ['label' => 'Выполнен', 'class' => 'text-bg-success'],
            'cancelled' => ['label' => 'Отменён', 'class' => 'text-bg-danger'],
        ];

        $currentStatus = (string) request('status', '');
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
                                    <div class="fs-xl fw-semibold text-dark-emphasis">{{ (int) ($statusCounts['completed'] ?? 0) }}</div>
                                    <div class="fs-xs text-body-secondary">Выполнено</div>
                                </div>
                            </div>
                        </div>

                        <h6 class="pt-1 ps-2 ms-1">Покупки</h6>
                        <nav class="list-group list-group-borderless">
                            <a class="list-group-item list-group-item-action d-flex align-items-center active" href="{{ route('profile.orders') }}">
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
                            <h1 class="h2 mb-1">История заказов</h1>
                            <p class="text-body-secondary mb-0">Все оформленные заказы, их статусы и быстрый переход к деталям.</p>
                        </div>
                        <a class="btn btn-outline-secondary d-lg-none" href="#accountSidebar" data-bs-toggle="offcanvas" aria-controls="accountSidebar">
                            <i class="ci-sidebar fs-base me-2"></i>
                            Меню кабинета
                        </a>
                    </div>

                    <ul class="nav nav-pills flex-nowrap overflow-auto gap-2 pb-2 mb-4">
                        @foreach ($statusTabs as $value => $tab)
                            <li class="nav-item flex-shrink-0">
                                <a
                                    href="{{ route('profile.orders', $value !== '' ? ['status' => $value] : []) }}"
                                    class="nav-link {{ $currentStatus === (string) $value ? 'active' : '' }}"
                                >
                                    {{ $tab['label'] }}
                                    <span class="badge {{ $currentStatus === (string) $value ? 'text-bg-light text-dark' : 'text-bg-secondary' }} rounded-pill ms-2">
                                        {{ $tab['badge'] }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    @if ($orders->isEmpty())
                        <div class="border rounded-5 p-5 text-center">
                            <div class="fs-1 text-body-tertiary mb-3">
                                <i class="ci-shopping-bag"></i>
                            </div>
                            <h2 class="h4 mb-2">Заказов пока нет</h2>
                            <p class="text-body-secondary mb-4">После оформления заказа он появится в этом разделе с актуальным статусом.</p>
                            <a href="{{ route('catalog.index') }}" class="btn btn-primary">Перейти в каталог</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle fs-sm mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-0 pb-3">Заказ</th>
                                        <th class="border-0 pb-3">Дата</th>
                                        <th class="border-0 pb-3">Статус</th>
                                        <th class="border-0 pb-3">Сумма</th>
                                        <th class="border-0 pb-3">Товаров</th>
                                        <th class="border-0 pb-3 text-end">Действие</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        @php($status = $statusBadges[$order->status] ?? ['label' => $order->status, 'class' => 'text-bg-secondary'])
                                        <tr>
                                            <td class="py-3">
                                                <div class="fw-semibold text-dark-emphasis">#{{ $order->number }}</div>
                                                <div class="text-body-secondary">{{ $order->payment_method_name ?: ($order->payment_method ?: 'Способ оплаты не указан') }}</div>
                                            </td>
                                            <td class="py-3">{{ $order->created_at?->format('d.m.Y H:i') }}</td>
                                            <td class="py-3">
                                                <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                                            </td>
                                            <td class="py-3">
                                                <div class="fw-semibold text-dark-emphasis">{{ number_format($order->total, 2, '.', ' ') }}</div>
                                                <div class="text-body-secondary">{{ $order->currency ?: 'BYN' }}</div>
                                            </td>
                                            <td class="py-3">{{ $order->items_count }}</td>
                                            <td class="py-3 text-end">
                                                <a href="{{ route('profile.order', $order) }}" class="btn btn-sm btn-outline-secondary">
                                                    Подробнее
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="pt-3 pb-2 pb-sm-0 mt-2 mt-md-3">
                            {{ $orders->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="fixed-bottom z-sticky w-100 btn btn-lg btn-dark border-0 border-top border-light border-opacity-10 rounded-0 pb-4 d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#accountSidebar" aria-controls="accountSidebar" data-bs-theme="light">
        <i class="ci-sidebar fs-base me-2"></i>
        Меню кабинета
    </button>
@endsection
