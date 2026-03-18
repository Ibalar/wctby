@props(['product'])

@php
    $firstSku = $product->skus->where('is_active', true)->first();
    $price = $firstSku?->price ?? $product->base_price;
    $oldPrice = $firstSku?->old_price;

    $imageUrl = $product->getFirstMediaUrl('images') ?: null;
    if (!$imageUrl && !empty($product->images[0])) {
        $imageUrl = asset('storage/' . $product->images[0]);
    }
    if (!$imageUrl) {
        $imageUrl = asset('assets/img/placeholder.jpg');
    }

    $flags = $product->flags ?? [];
@endphp

<div class="col">
    <div class="product-card animate-underline hover-effect-opacity bg-body rounded">
        <div class="position-relative">
            <div class="position-absolute top-0 end-0 z-2 hover-effect-target opacity-0 mt-3 me-3">
                <div class="d-flex flex-column gap-2">
                    <button type="button" class="btn btn-icon btn-secondary animate-pulse d-none d-lg-inline-flex" aria-label="Добавить в избранное">
                        <i class="ci-heart fs-base animate-target"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-secondary animate-rotate d-none d-lg-inline-flex" aria-label="Сравнить">
                        <i class="ci-refresh-cw fs-base animate-target"></i>
                    </button>
                </div>
            </div>

            <div class="dropdown d-lg-none position-absolute top-0 end-0 z-2 mt-2 me-2">
                <button type="button" class="btn btn-icon btn-sm btn-secondary bg-body" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Действия">
                    <i class="ci-more-vertical fs-lg"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end fs-xs p-2" style="min-width: auto">
                    <li>
                        <a class="dropdown-item" href="#!">
                            <i class="ci-heart fs-sm ms-n1 me-2"></i>
                            В избранное
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#!">
                            <i class="ci-refresh-cw fs-sm ms-n1 me-2"></i>
                            Сравнить
                        </a>
                    </li>
                </ul>
            </div>

            <a class="d-block rounded-top overflow-hidden p-3 p-sm-4" href="{{ route('catalog.product', $product->slug) }}">
                @foreach(array_slice($flags, 0, 2) as $index => $flag)
                    <span class="badge position-absolute top-0
                        {{ $index === 0 ? 'start-0' : 'end-0' }}
                        mt-2 ms-2 mt-lg-3 ms-lg-3"
                                          style="background-color: {{ $flag['color'] ?? '#2f6ed5' }};
                               color: {{ $flag['color_text'] ?? '#ffffff' }}">
                        {{ $flag['title'] ?? '' }}
                    </span>
                @endforeach
                <div class="ratio" style="--cz-aspect-ratio: calc(240 / 258 * 100%)">
                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                </div>
            </a>
        </div>

        <div class="w-100 min-w-0 px-1 pb-2 px-sm-3 pb-sm-3">
            <h3 class="pb-1 mb-2">
                <a class="d-block fs-sm fw-medium text-truncate" href="{{ route('catalog.product', $product->slug) }}">
                    <span class="animate-target">{{ $product->name }}</span>
                </a>
            </h3>

            <div class="d-flex align-items-center justify-content-between">
                <div class="h5 lh-1 mb-0">
                    {{ number_format($price, 2) }} BYN
                    @if($oldPrice && $oldPrice > $price)
                        <del class="text-body-tertiary fs-sm fw-normal">{{ number_format($oldPrice, 2) }} BYN</del>
                    @endif
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="ms-2">
                    @csrf
                    <input type="hidden" name="purchasable_type" value="product">
                    <input type="hidden" name="purchasable_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="product-card-button btn btn-icon btn-secondary animate-slide-end" aria-label="Добавить в корзину">
                        <i class="ci-shopping-cart fs-base animate-target"></i>
                    </button>
                </form>
            </div>
        </div>

        @if(!empty($product->properties))
            <div class="product-card-details position-absolute top-100 start-0 w-100 bg-body rounded-bottom shadow mt-n2 p-3 pt-1">
                <span class="position-absolute top-0 start-0 w-100 bg-body mt-n2 py-2"></span>
                <ul class="list-unstyled d-flex flex-column gap-2 m-0">
                    @foreach(array_slice($product->properties, 0, 4) as $prop)
                        <li class="d-flex align-items-center">
                            <span class="fs-xs">{{ $prop['name'] ?? '' }}:</span>
                            <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                            <span class="text-dark-emphasis fs-xs fw-medium text-end">{{ $prop['value'] ?? '' }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
