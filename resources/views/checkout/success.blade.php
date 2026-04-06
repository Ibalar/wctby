@extends('layouts.main')

@section('title', 'Заказ оформлен')

@section('content')
    <section class="container pb-5 pt-4 mb-2 mb-md-3 mb-lg-4 mb-xl-5">
        <div class="mx-auto" style="max-width: 860px;">
            <div class="bg-body-tertiary rounded-5 p-4 p-md-5 mb-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="d-flex align-items-center justify-content-center bg-success text-white rounded-circle flex-shrink-0" style="width: 3rem; height: 3rem; margin-top: -.125rem">
                        <i class="ci-check fs-4"></i>
                    </div>
                    <div>
                        <h1 class="h3 mb-1">Заказ успешно оформлен</h1>
                        <p class="text-body-secondary mb-0">Мы получили ваш заказ и скоро свяжемся для подтверждения.</p>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <div class="border rounded-4 p-3 h-100 bg-body">
                            <div class="fs-sm text-body-secondary mb-1">Номер заказа</div>
                            <div class="fw-semibold">{{ $order->number }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded-4 p-3 h-100 bg-body">
                            <div class="fs-sm text-body-secondary mb-1">Сумма</div>
                            <div class="fw-semibold">{{ number_format($order->total, 2) }} {{ $order->currency }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded-4 p-3 h-100 bg-body">
                            <div class="fs-sm text-body-secondary mb-1">Статус</div>
                            <div class="fw-semibold">Новый</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="bg-body-tertiary rounded-5 p-4 h-100">
                        <h2 class="h5 mb-3">Контакты</h2>
                        <div class="fs-sm d-flex flex-column gap-2">
                            <div><span class="text-body-secondary">Имя:</span> {{ $order->customer_name }}</div>
                            <div><span class="text-body-secondary">Телефон:</span> {{ $order->customer_phone }}</div>
                            <div><span class="text-body-secondary">Email:</span> {{ $order->customer_email ?: 'Не указан' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-body-tertiary rounded-5 p-4 h-100">
                        <h2 class="h5 mb-3">Доставка и оплата</h2>
                        <div class="fs-sm d-flex flex-column gap-2">
                            <div><span class="text-body-secondary">Доставка:</span> {{ $order->delivery_method_name ?? $order->delivery_method_code }}</div>
                            <div><span class="text-body-secondary">Оплата:</span> {{ $order->payment_method_name ?? $order->payment_method_code }}</div>
                            <div>
                                <span class="text-body-secondary">Адрес:</span>
                                {{ $order->shipping_address['city'] ?? '' }},
                                {{ $order->shipping_address['street'] ?? '' }},
                                дом {{ $order->shipping_address['house'] ?? '' }}
                                @if(!empty($order->shipping_address['apartment']))
                                    , кв. {{ $order->shipping_address['apartment'] }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-body-tertiary rounded-5 p-4 mt-4">
                <h2 class="h5 mb-4">Состав заказа</h2>
                <div class="vstack gap-3">
                    @foreach($order->items as $item)
                        <div class="d-flex justify-content-between gap-3">
                            <div>
                                <div class="fw-medium">{{ $item->name }}</div>
                                <div class="fs-sm text-body-secondary">
                                    {{ $item->quantity }} x {{ number_format($item->price, 2) }} {{ $order->currency }}
                                    @if($item->sku)
                                        · Артикул: {{ $item->sku }}
                                    @endif
                                </div>
                            </div>
                            <div class="fw-semibold text-nowrap">{{ number_format($item->line_total, 2) }} {{ $order->currency }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="d-flex flex-wrap gap-3 mt-4">
                <a href="{{ route('catalog.index') }}" class="btn btn-lg btn-primary">Продолжить покупки</a>
                <a href="{{ route('cart.index') }}" class="btn btn-lg btn-secondary">Перейти в корзину</a>
            </div>
        </div>
    </section>
@endsection
