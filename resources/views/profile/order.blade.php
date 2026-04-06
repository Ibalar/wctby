@extends('layouts.main')

@section('title', 'Заказ #' . $order->number)

@section('content')
    @php
        $statusBadges = [
            'new' => ['label' => 'Новый', 'class' => 'text-bg-secondary'],
            'processing' => ['label' => 'В обработке', 'class' => 'text-bg-info'],
            'paid' => ['label' => 'Оплачен', 'class' => 'text-bg-primary'],
            'completed' => ['label' => 'Выполнен', 'class' => 'text-bg-success'],
            'cancelled' => ['label' => 'Отменён', 'class' => 'text-bg-danger'],
        ];

        $status = $statusBadges[$order->status] ?? ['label' => $order->status, 'class' => 'text-bg-secondary'];
        $shippingAddress = is_array($order->shipping_address) ? $order->shipping_address : [];
        $shippingAmount = (float) ($order->shipping_amount ?? $order->delivery_price ?? 0);
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
                                    <div class="fs-xl fw-semibold text-dark-emphasis">#{{ $order->number }}</div>
                                    <div class="fs-xs text-body-secondary">Текущий заказ</div>
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
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                                <h1 class="h2 mb-0">Заказ #{{ $order->number }}</h1>
                                <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                            </div>
                            <p class="text-body-secondary mb-0">Оформлен {{ $order->created_at?->format('d.m.Y H:i') }}.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a class="btn btn-outline-secondary d-lg-none" href="#accountSidebar" data-bs-toggle="offcanvas" aria-controls="accountSidebar">
                                <i class="ci-sidebar fs-base me-2"></i>
                                Меню кабинета
                            </a>
                            <a href="{{ route('profile.orders') }}" class="btn btn-outline-secondary">Назад к заказам</a>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-xl-8">
                            <div class="border rounded-5 p-4 p-sm-5 mb-4">
                                <h2 class="h4 mb-4">Состав заказа</h2>

                                @if ($order->items->isEmpty())
                                    <div class="text-body-secondary">В заказе нет товаров.</div>
                                @else
                                    <div class="vstack gap-3">
                                        @foreach ($order->items as $item)
                                            <div class="d-flex align-items-center justify-content-between gap-3 border rounded-4 p-3">
                                                <div class="min-w-0">
                                                    <div class="fw-semibold text-dark-emphasis">{{ $item->name }}</div>
                                                    <div class="fs-sm text-body-secondary">
                                                        @if($item->sku)
                                                            SKU: {{ $item->sku }} ·
                                                        @endif
                                                        {{ number_format($item->price, 2, '.', ' ') }} BYN × {{ $item->quantity }}
                                                    </div>
                                                </div>
                                                <div class="fw-semibold text-nowrap">
                                                    {{ number_format($item->line_total, 2, '.', ' ') }} BYN
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="border rounded-5 p-4 p-sm-5 mb-4">
                                <h2 class="h4 mb-4">Контактные данные</h2>
                                <div class="row g-3 fs-sm">
                                    <div class="col-md-4 text-body-secondary">Получатель</div>
                                    <div class="col-md-8 fw-medium text-dark-emphasis">{{ $order->customer_name ?: 'Не указан' }}</div>

                                    <div class="col-md-4 text-body-secondary">Телефон</div>
                                    <div class="col-md-8 fw-medium text-dark-emphasis">{{ $order->customer_phone ?: 'Не указан' }}</div>

                                    <div class="col-md-4 text-body-secondary">Email</div>
                                    <div class="col-md-8 fw-medium text-dark-emphasis">{{ $order->customer_email ?: 'Не указан' }}</div>

                                    <div class="col-md-4 text-body-secondary">Доставка</div>
                                    <div class="col-md-8 fw-medium text-dark-emphasis">{{ $order->delivery_method_name ?: ($order->delivery_method_code ?: 'Не указана') }}</div>

                                    <div class="col-md-4 text-body-secondary">Оплата</div>
                                    <div class="col-md-8 fw-medium text-dark-emphasis">{{ $order->payment_method_name ?: ($order->payment_method_code ?: 'Не указана') }}</div>
                                </div>
                            </div>

                            <div class="border rounded-5 p-4 p-sm-5">
                                <h2 class="h4 mb-4">Адрес доставки</h2>

                                @if (!empty($shippingAddress))
                                    <div class="row g-3 fs-sm">
                                        <div class="col-md-4 text-body-secondary">Город</div>
                                        <div class="col-md-8 fw-medium text-dark-emphasis">{{ $shippingAddress['city'] ?? 'Не указан' }}</div>

                                        <div class="col-md-4 text-body-secondary">Улица</div>
                                        <div class="col-md-8 fw-medium text-dark-emphasis">{{ $shippingAddress['street'] ?? 'Не указана' }}</div>

                                        <div class="col-md-4 text-body-secondary">Дом</div>
                                        <div class="col-md-8 fw-medium text-dark-emphasis">{{ $shippingAddress['house'] ?? 'Не указан' }}</div>

                                        <div class="col-md-4 text-body-secondary">Квартира</div>
                                        <div class="col-md-8 fw-medium text-dark-emphasis">{{ $shippingAddress['apartment'] ?? 'Не указана' }}</div>
                                    </div>
                                @else
                                    <div class="text-body-secondary">Адрес доставки не указан.</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="position-sticky top-0" style="padding-top: 100px">
                                <div class="border rounded-5 p-4">
                                    <h2 class="h5 mb-4">Итоги заказа</h2>

                                    <ul class="list-unstyled fs-sm d-flex flex-column gap-3 mb-4">
                                        <li class="d-flex justify-content-between">
                                            <span class="text-body-secondary">Подытог</span>
                                            <span class="fw-medium">{{ number_format($order->subtotal, 2, '.', ' ') }} BYN</span>
                                        </li>
                                        <li class="d-flex justify-content-between">
                                            <span class="text-body-secondary">Доставка</span>
                                            <span class="fw-medium">{{ number_format($shippingAmount, 2, '.', ' ') }} BYN</span>
                                        </li>
                                        @if((float) $order->discount_amount > 0)
                                            <li class="d-flex justify-content-between">
                                                <span class="text-body-secondary">Скидка</span>
                                                <span class="fw-medium text-danger">-{{ number_format($order->discount_amount, 2, '.', ' ') }} BYN</span>
                                            </li>
                                        @endif
                                    </ul>

                                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                        <span class="h6 mb-0">Итого</span>
                                        <span class="h5 mb-0">{{ number_format($order->total, 2, '.', ' ') }} BYN</span>
                                    </div>
                                </div>
                            </div>
                        </div>
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
