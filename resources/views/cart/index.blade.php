@extends('layouts.main')

@section('content')


    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif

    <!-- Items in the cart + Order summary -->
    <section class="container pb-5 pt-3 mb-2 mb-md-3 mb-lg-4 mb-xl-5">
        <h1 class="h3 mb-4">Корзина</h1>
        <div class="row">

            <!-- Items list -->
            <div class="col-lg-8">
                <div class="pe-lg-2 pe-xl-3 me-xl-3">

                    @if($items->isEmpty())
                        <div>
                            <i class="ci-cart-empty fs-1 text-muted mb-3"></i>
                            <p class="text-muted">Ваша корзина пуста</p>
                            <a href="/" class="btn btn-outline-primary mt-3">Продолжить покупки</a>
                        </div>
                    @else
                        <!-- Free shipping progress -->
                        <p class="fs-sm">Купите еще на <span class="text-dark-emphasis fw-semibold">{{ number_format(max(0, 183 - $total), 2) }} BYN</span> чтобы получить <span class="text-dark-emphasis fw-semibold">Бесплатную доставку</span></p>
                        <div class="progress w-100 overflow-visible mb-4" role="progressbar" aria-label="Free shipping progress" aria-valuenow="{{ min(100, ($total / 183) * 100) }}" aria-valuemin="0" aria-valuemax="100" style="height: 4px">
                            <div class="progress-bar bg-warning rounded-pill position-relative overflow-visible" style="width: {{ min(100, ($total / 183) * 100) }}%; height: 4px">
                                <div class="position-absolute top-50 end-0 d-flex align-items-center justify-content-center translate-middle-y bg-body border border-warning rounded-circle me-n1" style="width: 1.5rem; height: 1.5rem">
                                    <i class="ci-star-filled text-warning"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Table of items -->
                        <table class="table position-relative z-2 mb-4">
                            <thead>
                            <tr>
                                <th scope="col" class="fs-sm fw-normal py-3 ps-0"><span class="text-body">Товар</span></th>
                                <th scope="col" class="text-body fs-sm fw-normal py-3 d-none d-xl-table-cell"><span class="text-body">Цена</span></th>
                                <th scope="col" class="text-body fs-sm fw-normal py-3 d-none d-md-table-cell"><span class="text-body">Количество</span></th>
                                <th scope="col" class="text-body fs-sm fw-normal py-3 d-none d-md-table-cell"><span class="text-body">Сумма</span></th>
                                <th scope="col" class="py-0 px-0">
                                    <div class="nav justify-content-end">
                                        <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="nav-link d-inline-block text-decoration-underline text-nowrap py-3 px-0 border-0 bg-transparent">Очистить корзину</button>
                                        </form>
                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="align-middle">

                            @foreach($items as $item)
                                @php
                                    $product = $item->purchasable_type === 'App\\Models\\Sku'
                                        ? $item->purchasable->product
                                        : $item->purchasable;
                                    $oldPrice = $product->old_price ?? null;
                                    $discount = $oldPrice ? round((1 - $item->price / $oldPrice) * 100) : 0;
                                @endphp

                                    <!-- Item -->
                                <tr>
                                    <td class="py-3 ps-0">
                                        <div class="d-flex align-items-center">
                                            <a class="position-relative flex-shrink-0" href="#">
                                                @if($discount > 0)
                                                    <span class="badge text-bg-danger position-absolute top-0 start-0">-{{ $discount }}%</span>
                                                @endif
                                                <img src="{{ $product->image ?? 'assets/img/shop/electronics/thumbs/placeholder.png' }}"
                                                     width="110"
                                                     alt="{{ $product->name ?? 'Товар' }}">
                                            </a>
                                            <div class="w-100 min-w-0 ps-2 ps-xl-3">
                                                <h5 class="d-flex animate-underline mb-2">
                                                    <a class="d-block fs-sm fw-medium text-truncate animate-target" href="#">
                                                        @if($item->purchasable_type === 'App\\Models\\Sku')
                                                            {{ $product->name ?? 'SKU #' . $item->purchasable_id }}
                                                        @else
                                                            {{ $product->name ?? 'Product #' . $item->purchasable_id }}
                                                        @endif
                                                    </a>
                                                </h5>
                                                <ul class="list-unstyled gap-1 fs-xs mb-0">
                                                    @if($item->purchasable_type === 'App\\Models\\Sku' && $item->purchasable->sku)
                                                        <li><span class="text-body-secondary">Артикул:</span> <span class="text-dark-emphasis fw-medium">{{ $item->purchasable->sku }}</span></li>
                                                    @endif
                                                    @if($product->color ?? false)
                                                        <li><span class="text-body-secondary">Цвет:</span> <span class="text-dark-emphasis fw-medium">{{ $product->color }}</span></li>
                                                    @endif
                                                    @if($product->model ?? false)
                                                        <li><span class="text-body-secondary">Модель:</span> <span class="text-dark-emphasis fw-medium">{{ $product->model }}</span></li>
                                                    @endif
                                                    <li class="d-xl-none">
                                                        <span class="text-body-secondary">Цена:</span>
                                                        <span class="text-dark-emphasis fw-medium">
                                                            {{ number_format($item->price, 2) }} BYN
                                                            @if($oldPrice && $oldPrice > $item->price)
                                                                <del class="text-body-tertiary fw-normal">{{ number_format($oldPrice, 2) }} BYN</del>
                                                            @endif
                                                        </span>
                                                    </li>
                                                </ul>
                                                <div class="count-input rounded-2 d-md-none mt-3">
                                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center gap-2">
                                                        @csrf
                                                        <button type="button" class="btn btn-sm btn-icon" data-decrement aria-label="Decrement quantity">
                                                            <i class="ci-minus"></i>
                                                        </button>
                                                        <input type="number" name="quantity" class="form-control form-control-sm" value="{{ $item->quantity }}" min="0" style="width: 60px;" readonly>
                                                        <button type="button" class="btn btn-sm btn-icon" data-increment aria-label="Increment quantity">
                                                            <i class="ci-plus"></i>
                                                        </button>
                                                        <button type="submit" class="btn btn-link btn-sm p-0" data-bs-toggle="tooltip" data-bs-title="Обновить">
                                                            <i class="ci-refresh"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="h6 py-3 d-none d-xl-table-cell">
                                        {{ number_format($item->price, 2) }} BYN
                                        @if($oldPrice && $oldPrice > $item->price)
                                            <del class="text-body-tertiary fs-xs fw-normal d-block">{{ number_format($oldPrice, 2) }} BYN</del>
                                        @endif
                                    </td>
                                    <td class="py-3 d-none d-md-table-cell">
                                        <div class="count-input">
                                            <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center gap-2">
                                                @csrf
                                                <button type="button" class="btn btn-icon" data-decrement aria-label="Decrement quantity">
                                                    <i class="ci-minus"></i>
                                                </button>
                                                <input type="number" name="quantity" class="form-control" value="{{ $item->quantity }}" min="0" style="width: 70px;" readonly>
                                                <button type="button" class="btn btn-icon" data-increment aria-label="Increment quantity">
                                                    <i class="ci-plus"></i>
                                                </button>
                                                <button type="submit" class="btn btn-link p-0" data-bs-toggle="tooltip" data-bs-title="Обновить">
                                                    <i class="ci-refresh"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="h6 py-3 d-none d-md-table-cell">
                                        {{ number_format($item->price * $item->quantity, 2) }} BYN
                                    </td>
                                    <td class="text-end py-3 px-0">
                                        <form action="{{ route('cart.remove', $item) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-close fs-sm" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-sm" data-bs-title="Удалить" aria-label="Remove from cart"></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="nav position-relative z-2 mb-4 mb-lg-0">
                            <a class="nav-link animate-underline px-0" href="/">
                                <i class="ci-chevron-left fs-lg me-1"></i>
                                <span class="animate-target">Продолжить покупки</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order summary (sticky sidebar) -->
            @if(!$items->isEmpty())
                <aside class="col-lg-4" style="margin-top: -100px">
                    <div class="position-sticky top-0" style="padding-top: 100px">
                        <div class="bg-body-tertiary rounded-5 p-4 mb-3">
                            <div class="p-sm-2 p-lg-0 p-xl-2">
                                <h5 class="border-bottom pb-4 mb-4">Сумма заказа</h5>
                                <ul class="list-unstyled fs-sm gap-3 mb-0">
                                    <li class="d-flex justify-content-between">
                                        Товаров ({{ $count }}):
                                        <span class="text-dark-emphasis fw-medium">{{ number_format($total, 2) }} BYN</span>
                                    </li>
                                    <li class="d-flex justify-content-between">
                                        Экономия:
                                        <span class="text-danger fw-medium">-{{ number_format($savings ?? 0, 2) }} BYN</span>
                                    </li>
                                    <li class="d-flex justify-content-between">
                                        Доставка:
                                        <span class="text-dark-emphasis fw-medium">Рассчитывается при оформлении</span>
                                    </li>
                                </ul>
                                <div class="border-top pt-4 mt-4">
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="fs-sm">Итого:</span>
                                        <span class="h5 mb-0">{{ number_format($total - ($savings ?? 0), 2) }} BYN</span>
                                    </div>
                                    <a class="btn btn-lg btn-primary w-100" href="{{ route('checkout.index') }}">
                                        Оформить заказ
                                        <i class="ci-chevron-right fs-lg ms-1 me-n1"></i>
                                    </a>
                                    <div class="nav justify-content-center fs-sm mt-3">
                                        <a class="nav-link text-decoration-underline p-0 me-1" href="#authForm" data-bs-toggle="offcanvas" role="button">Создать аккаунт</a>
                                        и получите
                                        <span class="text-dark-emphasis fw-medium ms-1">{{ floor($total * 0.1) }} бонусов</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion bg-body-tertiary rounded-5 p-4">
                            <div class="accordion-item border-0">
                                <h3 class="accordion-header" id="promoCodeHeading">
                                    <button type="button" class="accordion-button animate-underline collapsed py-0 ps-sm-2 ps-lg-0 ps-xl-2" data-bs-toggle="collapse" data-bs-target="#promoCode" aria-expanded="false" aria-controls="promoCode">
                                        <i class="ci-percent fs-xl me-2"></i>
                                        <span class="animate-target me-2">Применить промокод</span>
                                    </button>
                                </h3>
                                <div class="accordion-collapse collapse" id="promoCode" aria-labelledby="promoCodeHeading">
                                    <div class="accordion-body pt-3 pb-2 ps-sm-2 px-lg-0 px-xl-2">
                                        <form class="needs-validation d-flex gap-2" action="{{ route('cart.apply-promo') }}" method="POST" novalidate>
                                            @csrf
                                            <div class="position-relative w-100">
                                                <input type="text" name="promo_code" class="form-control" placeholder="Введите промокод" required>
                                                <div class="invalid-tooltip bg-transparent py-0">Введите корректный промокод!</div>
                                            </div>
                                            <button type="submit" class="btn btn-dark">Применить</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            @endif
        </div>
    </section>

@endsection
