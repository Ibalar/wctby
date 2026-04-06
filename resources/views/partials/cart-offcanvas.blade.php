@php
    $freeShippingThreshold = 183;
    $remainingForFreeShipping = max(0, $freeShippingThreshold - ($cartTotal ?? 0));
    $freeShippingProgress = $freeShippingThreshold > 0
        ? min(100, (($cartTotal ?? 0) / $freeShippingThreshold) * 100)
        : 0;
@endphp

<div class="offcanvas offcanvas-end pb-sm-2 px-sm-2" id="shoppingCart" tabindex="-1" aria-labelledby="shoppingCartLabel" style="width: 500px">
    <div class="offcanvas-header flex-column align-items-start py-3 pt-lg-4">
        <div class="d-flex align-items-center justify-content-between w-100 mb-3 mb-lg-4">
            <h4 class="offcanvas-title" id="shoppingCartLabel">Корзина</h4>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        @if(($cartCount ?? 0) > 0)
            <p class="fs-sm mb-2">
                @if($remainingForFreeShipping > 0)
                    Купите еще на <span class="text-dark-emphasis fw-semibold">{{ number_format($remainingForFreeShipping, 2) }} BYN</span>
                    для получения <span class="text-dark-emphasis fw-semibold">бесплатной доставки</span>
                @else
                    <span class="text-dark-emphasis fw-semibold">Бесплатная доставка</span> уже доступна для этого заказа
                @endif
            </p>
            <div class="progress w-100" role="progressbar" aria-label="Прогресс бесплатной доставки" aria-valuenow="{{ $freeShippingProgress }}" aria-valuemin="0" aria-valuemax="100" style="height: 4px">
                <div class="progress-bar bg-warning rounded-pill" style="width: {{ $freeShippingProgress }}%"></div>
            </div>
        @else
            <p class="fs-sm text-body-secondary mb-0">Добавьте товары в корзину, чтобы перейти к оформлению заказа.</p>
        @endif
    </div>

    <div class="offcanvas-body d-flex flex-column gap-4 pt-2">
        @forelse($cartItems ?? collect() as $item)
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
            @endphp

            <div class="d-flex align-items-center">
                <a class="flex-shrink-0" href="{{ $productUrl }}">
                    <img src="{{ $imageUrl }}" width="110" alt="{{ $productName }}">
                </a>
                <div class="w-100 min-w-0 ps-2 ps-sm-3">
                    <h5 class="d-flex animate-underline mb-2">
                        <a class="d-block fs-sm fw-medium text-truncate animate-target" href="{{ $productUrl }}">{{ $productName }}</a>
                    </h5>

                    @if($purchasable instanceof \App\Models\Sku && $purchasable->attributeOptions->isNotEmpty())
                        <div class="fs-xs text-body-secondary mb-2">
                            {{ $purchasable->attributeOptions->map(fn ($option) => ($option->attribute->name ?? '') . ': ' . $option->value)->implode(', ') }}
                        </div>
                    @endif

                    <div class="h6 pb-1 mb-2">
                        {{ number_format($item->price, 2) }} BYN
                        @if($oldPrice && $oldPrice > $item->price)
                            <del class="text-body-tertiary fs-xs fw-normal">{{ number_format($oldPrice, 2) }} BYN</del>
                        @endif
                    </div>

                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <form action="{{ route('cart.update', $item) }}" method="POST" class="count-input rounded-2" data-auto-submit="quantity">
                            @csrf
                            <button type="button" class="btn btn-icon btn-sm" data-decrement aria-label="Уменьшить количество">
                                <i class="ci-minus"></i>
                            </button>
                            <input type="number" name="quantity" class="form-control form-control-sm" value="{{ $item->quantity }}" min="0" readonly>
                            <button type="button" class="btn btn-icon btn-sm" data-increment aria-label="Увеличить количество">
                                <i class="ci-plus"></i>
                            </button>
                        </form>

                        <form action="{{ route('cart.remove', $item) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-close fs-sm" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-sm" data-bs-title="Удалить" aria-label="Удалить из корзины"></button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="ci-cart-empty fs-1 text-body-tertiary d-block mb-3"></i>
                <p class="text-body-secondary mb-0">Корзина пока пуста</p>
            </div>
        @endforelse
    </div>

    <div class="offcanvas-header flex-column align-items-start">
        <div class="d-flex align-items-center justify-content-between w-100 mb-3 mb-md-4">
            <span class="text-light-emphasis">Итого:</span>
            <span class="h6 mb-0">{{ number_format($cartTotal ?? 0, 2) }} BYN</span>
        </div>

        @if(($cartSavings ?? 0) > 0)
            <div class="fs-sm text-success mb-3">Экономия: {{ number_format($cartSavings, 2) }} BYN</div>
        @endif

        <div class="d-flex w-100 gap-3">
            <a class="btn btn-lg btn-secondary w-100" href="{{ route('cart.index') }}">В корзину</a>
            <a class="btn btn-lg btn-primary w-100 {{ ($cartCount ?? 0) === 0 ? 'disabled' : '' }}" href="{{ route('checkout.index') }}">Оформить заказ</a>
        </div>
    </div>
</div>
