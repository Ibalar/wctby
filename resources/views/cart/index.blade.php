@extends('layouts.main')

@section('title', 'Корзина')

@section('content')
    @php
        $freeShippingThreshold = 183;
        $remainingForFreeShipping = max(0, $freeShippingThreshold - $total);
        $freeShippingProgress = min(100, ($total / $freeShippingThreshold) * 100);
    @endphp

    <section class="container pb-5 pt-4 mb-2 mb-md-3 mb-lg-4 mb-xl-5">
        <h1 class="h3 mb-4">Корзина</h1>

        <div class="row g-4">
            <div class="col-lg-8">
                @if($items->isEmpty())
                    <div class="bg-body-tertiary rounded-5 p-5 text-center">
                        <i class="ci-cart-empty fs-1 text-body-tertiary d-block mb-3"></i>
                        <p class="text-body-secondary mb-4">Ваша корзина пуста</p>
                        <a href="{{ route('catalog.index') }}" class="btn btn-primary">Перейти в каталог</a>
                    </div>
                @else
                    <p class="fs-sm mb-2">
                        @if($remainingForFreeShipping > 0)
                            Купите еще на <span class="text-dark-emphasis fw-semibold">{{ number_format($remainingForFreeShipping, 2) }} BYN</span>,
                            чтобы получить <span class="text-dark-emphasis fw-semibold">бесплатную доставку</span>
                        @else
                            Бесплатная доставка уже доступна для этого заказа
                        @endif
                    </p>

                    <div class="progress w-100 overflow-visible mb-4" role="progressbar" aria-label="Free shipping progress" aria-valuenow="{{ $freeShippingProgress }}" aria-valuemin="0" aria-valuemax="100" style="height: 4px">
                        <div class="progress-bar bg-warning rounded-pill position-relative overflow-visible" style="width: {{ $freeShippingProgress }}%; height: 4px">
                            <div class="position-absolute top-50 end-0 d-flex align-items-center justify-content-center translate-middle-y bg-body border border-warning rounded-circle me-n1" style="width: 1.5rem; height: 1.5rem">
                                <i class="ci-star-filled text-warning"></i>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                            <tr>
                                <th class="ps-0">Товар</th>
                                <th class="d-none d-xl-table-cell">Цена</th>
                                <th class="d-none d-md-table-cell">Количество</th>
                                <th class="d-none d-md-table-cell">Сумма</th>
                                <th class="text-end pe-0">
                                    <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-link p-0 text-decoration-underline">Очистить корзину</button>
                                    </form>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                @php
                                    $purchasable = $item->purchasable;
                                    $product = $purchasable instanceof \App\Models\Sku ? $purchasable->product : $purchasable;
                                    $productName = $product->name ?? ('Товар #' . $item->purchasable_id);
                                    $productUrl = $product?->slug ? route('catalog.product', $product->slug) : route('cart.index');
                                    $imageUrl = $product?->getFirstMediaUrl('images');
                                    if (!$imageUrl && !empty($product?->images[0])) {
                                        $imageUrl = asset('storage/' . $product->images[0]);
                                    }
                                    $imageUrl = $imageUrl ?: asset('assets/img/placeholder.jpg');
                                    $oldPrice = $purchasable instanceof \App\Models\Sku ? $purchasable->old_price : null;
                                    $discount = $oldPrice && $oldPrice > $item->price ? round((1 - $item->price / $oldPrice) * 100) : 0;
                                @endphp

                                <tr>
                                    <td class="ps-0 py-3">
                                        <div class="d-flex align-items-center">
                                            <a class="position-relative flex-shrink-0" href="{{ $productUrl }}">
                                                @if($discount > 0)
                                                    <span class="badge text-bg-danger position-absolute top-0 start-0">-{{ $discount }}%</span>
                                                @endif
                                                <img src="{{ $imageUrl }}" width="110" alt="{{ $productName }}">
                                            </a>
                                            <div class="w-100 min-w-0 ps-3">
                                                <h2 class="h6 mb-2">
                                                    <a class="text-decoration-none" href="{{ $productUrl }}">{{ $productName }}</a>
                                                </h2>

                                                @if($purchasable instanceof \App\Models\Sku)
                                                    <div class="fs-xs text-body-secondary mb-2">
                                                        @if($purchasable->sku)
                                                            <span class="d-inline-block me-2">Артикул: {{ $purchasable->sku }}</span>
                                                        @endif
                                                        @if($purchasable->attributeOptions->isNotEmpty())
                                                            <span>{{ $purchasable->attributeOptions->map(fn ($option) => ($option->attribute->name ?? '') . ': ' . $option->value)->implode(', ') }}</span>
                                                        @endif
                                                    </div>
                                                @endif

                                                <div class="d-md-none">
                                                    <div class="fw-medium mb-2">
                                                        {{ number_format($item->price, 2) }} BYN
                                                        @if($oldPrice && $oldPrice > $item->price)
                                                            <del class="text-body-tertiary fw-normal ms-1">{{ number_format($oldPrice, 2) }} BYN</del>
                                                        @endif
                                                    </div>

                                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="d-inline-flex align-items-center gap-2" data-auto-submit="quantity">
                                                        @csrf
                                                        <div class="count-input rounded-2">
                                                            <button type="button" class="btn btn-sm btn-icon" data-decrement aria-label="Уменьшить количество">
                                                                <i class="ci-minus"></i>
                                                            </button>
                                                            <input type="number" name="quantity" class="form-control form-control-sm" value="{{ $item->quantity }}" min="0" style="width: 60px" readonly>
                                                            <button type="button" class="btn btn-sm btn-icon" data-increment aria-label="Увеличить количество">
                                                                <i class="ci-plus"></i>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-xl-table-cell">
                                        {{ number_format($item->price, 2) }} BYN
                                        @if($oldPrice && $oldPrice > $item->price)
                                            <del class="d-block text-body-tertiary fs-xs fw-normal">{{ number_format($oldPrice, 2) }} BYN</del>
                                        @endif
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="d-inline-flex align-items-center gap-2" data-auto-submit="quantity">
                                            @csrf
                                            <div class="count-input">
                                                <button type="button" class="btn btn-icon" data-decrement aria-label="Уменьшить количество">
                                                    <i class="ci-minus"></i>
                                                </button>
                                                <input type="number" name="quantity" class="form-control" value="{{ $item->quantity }}" min="0" style="width: 70px" readonly>
                                                <button type="button" class="btn btn-icon" data-increment aria-label="Увеличить количество">
                                                    <i class="ci-plus"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="d-none d-md-table-cell fw-semibold">{{ number_format($item->price * $item->quantity, 2) }} BYN</td>
                                    <td class="text-end pe-0">
                                        <form action="{{ route('cart.remove', $item) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-close fs-sm" aria-label="Удалить из корзины"></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="nav mt-4">
                        <a class="nav-link px-0" href="{{ route('catalog.index') }}">
                            <i class="ci-chevron-left fs-lg me-1"></i>
                            Продолжить покупки
                        </a>
                    </div>
                @endif
            </div>

            @if($items->isNotEmpty())
                <aside class="col-lg-4">
                    <div class="position-sticky top-0" style="padding-top: 100px">
                        <div class="bg-body-tertiary rounded-5 p-4">
                            <h2 class="h5 border-bottom pb-4 mb-4">Сумма заказа</h2>

                            <ul class="list-unstyled fs-sm d-flex flex-column gap-3 mb-0">
                                <li class="d-flex justify-content-between">
                                    <span>Товаров ({{ $count }})</span>
                                    <span class="fw-medium">{{ number_format($total, 2) }} BYN</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span>Экономия</span>
                                    <span class="text-danger fw-medium">-{{ number_format($savings, 2) }} BYN</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span>Доставка</span>
                                    <span class="fw-medium">Рассчитывается при оформлении</span>
                                </li>
                            </ul>

                            <div class="border-top pt-4 mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fs-sm">Итого</span>
                                    <span class="h5 mb-0">{{ number_format($total, 2) }} BYN</span>
                                </div>

                                <a class="btn btn-lg btn-primary w-100" href="{{ route('checkout.index') }}">
                                    Оформить заказ
                                    <i class="ci-chevron-right fs-lg ms-1 me-n1"></i>
                                </a>

                                <div class="text-body-secondary fs-sm mt-3">
                                    Бонусы после покупки: <span class="text-dark-emphasis fw-medium">{{ floor($total * 0.1) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            @endif
        </div>
    </section>
@endsection
