@extends('layouts.main')

@section('title', 'Оформление заказа')

@section('content')
    <section class="container pb-5 pt-4 mb-2 mb-md-3 mb-lg-4 mb-xl-5">
        <h1 class="h3 mb-4">Оформление заказа</h1>

        @if($items->isEmpty())
            <div class="bg-body-tertiary rounded-5 p-5 text-center">
                <i class="ci-cart-empty fs-1 text-body-tertiary d-block mb-3"></i>
                <p class="text-body-secondary mb-4">Корзина пуста. Сначала добавьте товары.</p>
                <a href="{{ route('catalog.index') }}" class="btn btn-primary">Перейти в каталог</a>
            </div>
        @else
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-lg-7">
                    <form action="{{ route('checkout.process') }}" method="POST" class="vstack gap-4">
                        @csrf

                        <div class="bg-body-tertiary rounded-5 p-4">
                            <h2 class="h5 mb-4">Контактные данные</h2>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="customer_name" class="form-label">Имя</label>
                                    <input type="text" id="customer_name" name="customer_name" class="form-control" value="{{ old('customer_name', auth()->user()?->name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="customer_phone" class="form-label">Телефон</label>
                                    <input type="text" id="customer_phone" name="customer_phone" class="form-control" value="{{ old('customer_phone', auth()->user()?->phone) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="customer_email" class="form-label">Email</label>
                                    <input type="email" id="customer_email" name="customer_email" class="form-control" value="{{ old('customer_email', auth()->user()?->email) }}">
                                </div>
                            </div>
                        </div>

                        <div class="bg-body-tertiary rounded-5 p-4">
                            <h2 class="h5 mb-4">Способ доставки</h2>
                            @if($deliveryMethods->isEmpty())
                                <div class="alert alert-warning mb-0">Нет активных способов доставки. Добавьте их в админке.</div>
                            @else
                            <div class="vstack gap-3">
                                @foreach($deliveryMethods as $method)
                                    <label class="border rounded-4 p-3 bg-body cursor-pointer">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="radio" name="delivery_method" value="{{ $method->id }}" id="delivery_{{ $method->id }}" @checked((string) $defaultDeliveryMethodId === (string) $method->id) required>
                                            <span class="form-check-label d-flex justify-content-between w-100" for="delivery_{{ $method->id }}">
                                                <span>
                                                    <span class="fw-semibold d-block">{{ $method->name }}</span>
                                                    @if($method->description)
                                                        <span class="text-body-secondary fs-sm">{{ $method->description }}</span>
                                                    @endif
                                                </span>
                                                <span class="fw-semibold">{{ number_format($method->price, 2) }} BYN</span>
                                            </span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <div class="bg-body-tertiary rounded-5 p-4">
                            <h2 class="h5 mb-4">Способ оплаты</h2>
                            @if($paymentMethods->isEmpty())
                                <div class="alert alert-warning mb-0">Нет активных способов оплаты. Добавьте их в админке.</div>
                            @else
                            <div class="vstack gap-3">
                                @foreach($paymentMethods as $method)
                                    <label class="border rounded-4 p-3 bg-body cursor-pointer">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="radio" name="payment_method" value="{{ $method->id }}" id="payment_{{ $method->id }}" @checked((string) $defaultPaymentMethodId === (string) $method->id) required>
                                            <span class="form-check-label d-block" for="payment_{{ $method->id }}">
                                                <span class="fw-semibold d-block">{{ $method->name }}</span>
                                                @if($method->description)
                                                    <span class="text-body-secondary fs-sm">{{ $method->description }}</span>
                                                @endif
                                            </span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <div class="bg-body-tertiary rounded-5 p-4">
                            <h2 class="h5 mb-4">Адрес доставки</h2>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="city" class="form-label">Город</label>
                                    <input type="text" id="city" name="city" class="form-control" value="{{ old('city') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="street" class="form-label">Улица</label>
                                    <input type="text" id="street" name="street" class="form-control" value="{{ old('street') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="house" class="form-label">Дом</label>
                                    <input type="text" id="house" name="house" class="form-control" value="{{ old('house') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="apartment" class="form-label">Квартира</label>
                                    <input type="text" id="apartment" name="apartment" class="form-control" value="{{ old('apartment') }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <a href="{{ route('cart.index') }}" class="btn btn-lg btn-secondary">Вернуться в корзину</a>
                            <button type="submit" class="btn btn-lg btn-primary">Подтвердить заказ</button>
                        </div>
                    </form>
                </div>

                <aside class="col-lg-5">
                    <div class="position-sticky top-0" style="padding-top: 100px">
                        <div class="bg-body-tertiary rounded-5 p-4">
                            <h2 class="h5 mb-4">Ваш заказ</h2>

                            <div class="vstack gap-3 mb-4">
                                @foreach($items as $item)
                                    @php
                                        $purchasable = $item->purchasable;
                                        $product = $purchasable instanceof \App\Models\Sku ? $purchasable->product : $purchasable;
                                        $productName = $product->name ?? ('Товар #' . $item->purchasable_id);
                                        $imageUrl = $product?->getFirstMediaUrl('images');
                                        if (!$imageUrl && !empty($product?->images[0])) {
                                            $imageUrl = asset('storage/' . $product->images[0]);
                                        }
                                        $imageUrl = $imageUrl ?: asset('assets/img/placeholder.jpg');
                                    @endphp

                                    <div class="d-flex align-items-center">
                                        <img src="{{ $imageUrl }}" width="72" alt="{{ $productName }}" class="rounded-3">
                                        <div class="ps-3 min-w-0 flex-grow-1">
                                            <div class="fw-medium text-truncate">{{ $productName }}</div>
                                            <div class="fs-sm text-body-secondary">{{ $item->quantity }} x {{ number_format($item->price, 2) }} BYN</div>
                                        </div>
                                        <div class="fw-semibold">{{ number_format($item->price * $item->quantity, 2) }} BYN</div>
                                    </div>
                                @endforeach
                            </div>

                            <ul class="list-unstyled fs-sm d-flex flex-column gap-3 mb-0">
                                <li class="d-flex justify-content-between">
                                    <span>Товары</span>
                                    <span class="fw-medium">{{ number_format($total, 2) }} BYN</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span>Экономия</span>
                                    <span class="text-danger fw-medium">-{{ number_format($savings, 2) }} BYN</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span>Доставка</span>
                                    <span class="fw-medium">По выбранному способу</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        @endif
    </section>
@endsection
