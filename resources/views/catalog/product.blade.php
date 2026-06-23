@extends('layouts.main')

@section('title', $product->meta_title ?: $product->name)

@section('content')
    @php
        $activeSkus = $product->skus->where('is_active', true)->values();
        $defaultSku = $activeSkus->first();
        $currentPrice = $defaultSku?->price ?? $product->base_price;
        $currentOldPrice = $defaultSku?->old_price;
        $gallery = $product->images ?? [];
        $fallbackImage = $product->getFirstMediaUrl('images');

        if (empty($gallery) && $fallbackImage) {
            $gallery = [$fallbackImage];
        }

        $gallery = collect($gallery)->map(function ($image) {
            return str_starts_with($image, 'http') ? $image : asset('storage/' . ltrim($image, '/'));
        })->all();

        if (empty($gallery)) {
            $gallery = [asset('assets/img/placeholder.jpg')];
        }
    @endphp

    <x-breadcrumbs :items="$breadcrumbs" />

    <section class="container pt-md-4 pb-5 mt-md-2 mt-lg-3 mb-2 mb-sm-3 mb-lg-4 mb-xl-5">
        <div class="row align-items-start">
            <div class="col-md-6 col-lg-7 sticky-md-top z-1 mb-4 mb-md-0">
                <div class="d-flex">
                    @if(count($gallery) > 1)
                        <div class="swiper swiper-load swiper-thumbs d-none d-lg-block w-100 me-xl-3" id="thumbs" data-swiper='{
                            "direction": "vertical",
                            "spaceBetween": 12,
                            "slidesPerView": 4,
                            "watchSlidesProgress": true
                        }' style="max-width: 96px; height: 420px;">
                            <div class="swiper-wrapper flex-column">
                                @foreach($gallery as $image)
                                    <div class="swiper-slide swiper-thumb">
                                        <div class="ratio ratio-1x1" style="max-width: 94px">
                                            <img src="{{ $image }}" class="swiper-thumb-img" alt="{{ $product->name }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="swiper w-100" data-swiper='{
                        "loop": false,
                        @if(count($gallery) > 1)
                        "thumbs": {
                          "swiper": "#thumbs"
                        },
                        @endif
                        "pagination": {
                          "el": ".swiper-pagination",
                          "clickable": true
                        }
                    }'>
                        <div class="swiper-wrapper">
                            @foreach($gallery as $image)
                                <div class="swiper-slide">
                                    <a class="ratio ratio-1x1 d-block cursor-zoom-in"
                                       href="{{ $image }}"
                                       data-glightbox
                                       data-gallery="product-gallery">
                                        <img src="{{ $image }}" loading="lazy" alt="{{ $product->name }}">
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <div class="swiper-pagination mb-n4 d-lg-none"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-5 position-relative">
                <div class="ps-md-4 ps-xl-0">
                    <div class="position-relative" id="zoomPane">
                        <h1 class="h2 mb-3">{{ $product->name }}</h1>

                        @if($product->short_description)
                            <p class="text-body-secondary mb-4">{{ $product->short_description }}</p>
                        @endif

                        <div class="d-flex flex-wrap align-items-end gap-3 mb-4">
                            <div class="h2 mb-0" data-product-price>{{ number_format($currentPrice, 2) }} BYN</div>
                            <div class="text-body-tertiary fs-lg @if(!$currentOldPrice || $currentOldPrice <= $currentPrice) d-none @endif" data-product-old-price>
                                <del>{{ $currentOldPrice ? number_format($currentOldPrice, 2) . ' BYN' : '' }}</del>
                            </div>
                            <div class="d-flex align-items-center text-success fs-sm ms-auto">
                                <i class="ci-check-circle fs-base me-2"></i>
                                В наличии
                            </div>
                        </div>

                        <form action="{{ route('cart.add') }}" method="POST" class="vstack gap-4" id="productAddToCartForm">
                            @csrf

                            @if($activeSkus->isNotEmpty())
                                <div>
                                    <label for="skuSelect" class="form-label fw-semibold pb-1 mb-2">Вариант товара</label>
                                    <select id="skuSelect" name="purchasable_id" class="form-select" data-sku-select>
                                        @foreach($activeSkus as $sku)
                                            <option
                                                value="{{ $sku->id }}"
                                                data-price="{{ $sku->price }}"
                                                data-old-price="{{ $sku->old_price }}"
                                                data-sku-code="{{ $sku->sku }}"
                                                @selected($loop->first)
                                            >
                                                {{ $sku->attributeOptions->map(fn ($option) => ($option->attribute->name ?? '') . ': ' . $option->value)->implode(', ') ?: ($sku->sku ?: 'SKU #' . $sku->id) }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <div class="fs-sm text-body-secondary mt-2 @if(!$defaultSku?->sku) d-none @endif" data-sku-code>
                                        @if($defaultSku?->sku)
                                            Артикул: {{ $defaultSku->sku }}
                                        @endif
                                    </div>
                                </div>

                                <input type="hidden" name="purchasable_type" value="sku">
                            @else
                                <input type="hidden" name="purchasable_type" value="product">
                                <input type="hidden" name="purchasable_id" value="{{ $product->id }}">
                                @if($product->sku)
                                    <div class="fs-sm text-body-secondary">Артикул: {{ $product->sku }}</div>
                                @endif
                            @endif

                            <div class="d-flex flex-wrap flex-sm-nowrap flex-md-wrap flex-lg-nowrap gap-3 gap-lg-2 gap-xl-3 mb-4">
                                <div class="count-input flex-shrink-0 order-sm-1">
                                    <button type="button" class="btn btn-icon btn-lg" data-decrement aria-label="Уменьшить количество">
                                        <i class="ci-minus"></i>
                                    </button>
                                    <input type="number" name="quantity" class="form-control form-control-lg" value="1" min="1">
                                    <button type="button" class="btn btn-icon btn-lg" data-increment aria-label="Увеличить количество">
                                        <i class="ci-plus"></i>
                                    </button>
                                </div>

                                <button type="submit" class="btn btn-lg btn-primary w-100 animate-slide-end order-sm-2 order-md-4 order-lg-2">
                                    <i class="ci-shopping-cart fs-lg animate-target ms-n1 me-2"></i>
                                    Заказать
                                </button>

                                <button 
                                    type="button" 
                                    class="btn btn-lg btn-outline-secondary wishlist-toggle-btn order-sm-3 order-md-2 order-lg-3"
                                    data-product-id="{{ $product->id }}"
                                    aria-label="Добавить в избранное"
                                >
                                    <i class="ci-heart fs-lg"></i>
                                </button>
                            </div>
                        </form>

                        @if(!empty($product->properties))
                            <div class="mt-5">
                                <h2 class="h5 mb-3">Характеристики</h2>
                                <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                                    @foreach($product->properties as $property)
                                        <li class="d-flex align-items-center">
                                            <span>{{ $property['name'] ?? '' }}</span>
                                            <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                            <span class="text-dark-emphasis fw-medium text-end">{{ $property['value'] ?? '' }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($product->description)
                            <div class="mt-5">
                                <h2 class="h5 mb-3">Описание</h2>
                                <div class="text-body-secondary">{!! $product->safe_description !!}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    @if(Schema::hasTable('reviews'))
    @php
        $reviews = $product->reviews()->approved()->with('user')->latest()->get();
    @endphp
    <section class="container pb-5 mb-2 mb-md-3">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="h3 mb-0">
                Отзывы
                @if($product->reviews_count > 0)
                    <span class="fs-base fw-normal text-body-tertiary ms-2">
                        ({{ $product->reviews_count }})
                        ★ {{ number_format($product->average_rating, 1) }}
                    </span>
                @endif
            </h2>
        </div>

        @auth
            <div class="card border mb-4">
                <div class="card-body">
                    <h5 class="card-title">Оставить отзыв</h5>
                    <form data-review-form>
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="mb-3">
                            <label class="form-label">Оценка</label>
                            <div class="d-flex gap-1" data-review-stars>
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" class="btn btn-icon btn-outline-warning btn-sm" data-rating="{{ $i }}" title="{{ $i }}">
                                        <i class="ci-star fs-base"></i>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" data-review-rating value="5">
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="title" placeholder="Заголовок (необязательно)" maxlength="255">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" name="body" rows="3" placeholder="Ваш отзыв" required maxlength="5000"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Отправить</button>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-light text-center mb-4">
                <a href="{{ route('login') }}" class="fw-medium">Войдите</a>, чтобы оставить отзыв.
            </div>
        @endauth

        @if($reviews->isEmpty())
            <p class="text-body-tertiary">Пока нет отзывов. Будьте первым!</p>
        @else
            @foreach($reviews as $review)
                <div class="border rounded-4 p-4 mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <span class="fw-medium me-2">{{ $review->user?->display_name ?? 'Пользователь' }}</span>
                        <span class="text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="ci-star{{ $i <= $review->rating ? '-filled' : '' }} fs-sm"></i>
                            @endfor
                        </span>
                        <span class="text-body-tertiary ms-auto fs-sm">{{ $review->created_at->format('d.m.Y') }}</span>
                    </div>
                    @if($review->title)
                        <h6 class="mb-1">{{ $review->title }}</h6>
                    @endif
                    <p class="mb-0">{{ $review->body }}</p>
                </div>
            @endforeach
        @endif
    </section>
    @endif

    @if($relatedProducts->isNotEmpty())
        <section class="container pb-5 mb-2 mb-md-3 mb-lg-4 mb-xl-5">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="h3 mb-0">Похожие товары</h2>
                <a href="{{ route('catalog.category', $product->category->slug) }}" class="nav-link px-0">Все товары категории</a>
            </div>

            <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-4">
                @foreach($relatedProducts as $relatedProduct)
                    <x-product-card :product="$relatedProduct" />
                @endforeach
            </div>
        </section>
    @endif
@endsection

@push('meta')
<meta name="description" content="{{ $product->meta_description ?: Str::limit(strip_tags($product->description ?? $product->name), 160) }}">
@endpush

@push('open_graph')
<meta property="og:image" content="{{ $product->getFirstMediaUrl('images') ?: asset('assets/app-icons/icon-180x180.png') }}">
<meta property="og:type" content="product">
@endpush

@push('json_ld')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Product",
    "name": "{{ $product->name }}",
    "description": "{{ Str::limit(strip_tags($product->description ?? $product->name), 200) }}",
    "sku": "{{ $product->sku }}",
    "image": "{{ $product->getFirstMediaUrl('images') }}",
@if($product->category)
    "category": "{{ $product->category->name }}",
@endif
    "offers": {
        "@@type": "Offer",
        "priceCurrency": "BYN",
        "price": "{{ $product->base_price }}",
        "availability": "{{ $product->is_active ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "url": "{{ url()->current() }}"
    }
}
</script>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const skuSelect = document.querySelector('[data-sku-select]');
            if (!skuSelect) {
                return;
            }

            const priceNode = document.querySelector('[data-product-price]');
            const oldPriceNode = document.querySelector('[data-product-old-price]');
            const skuCodeNode = document.querySelector('[data-sku-code]');
            const formatPrice = (value) => `${Number(value).toFixed(2)} BYN`;

            const syncPrice = () => {
                const selectedOption = skuSelect.options[skuSelect.selectedIndex];
                const price = selectedOption.dataset.price;
                const oldPrice = selectedOption.dataset.oldPrice;
                const skuCode = selectedOption.dataset.skuCode;

                if (priceNode) {
                    priceNode.textContent = formatPrice(price);
                }

                if (oldPriceNode) {
                    if (oldPrice && Number(oldPrice) > Number(price)) {
                        oldPriceNode.classList.remove('d-none');
                        oldPriceNode.innerHTML = `<del>${formatPrice(oldPrice)}</del>`;
                    } else {
                        oldPriceNode.classList.add('d-none');
                        oldPriceNode.innerHTML = '';
                    }
                }

                if (skuCodeNode) {
                    if (skuCode) {
                        skuCodeNode.classList.remove('d-none');
                        skuCodeNode.textContent = `Артикул: ${skuCode}`;
                    } else {
                        skuCodeNode.classList.add('d-none');
                        skuCodeNode.textContent = '';
                    }
                }
            };

            skuSelect.addEventListener('change', syncPrice);
            syncPrice();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('[data-review-form]');
            if (!form) return;

            const stars = document.querySelectorAll('[data-review-stars] button');
            const ratingInput = document.querySelector('[data-review-rating]');

            stars.forEach(btn => {
                btn.addEventListener('click', () => {
                    const rating = btn.dataset.rating;
                    ratingInput.value = rating;
                    stars.forEach((b, i) => {
                        b.classList.toggle('btn-outline-warning', i >= rating);
                        b.classList.toggle('btn-warning', i < rating);
                    });
                });
            });

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const btn = form.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.textContent = 'Отправка...';

                try {
                    const res = await fetch('{{ route('reviews.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            product_id: form.querySelector('[name="product_id"]').value,
                            rating: ratingInput.value,
                            title: form.querySelector('[name="title"]').value,
                            body: form.querySelector('[name="body"]').value,
                        }),
                    });

                    const data = await res.json();

                    if (res.ok) {
                        showFlashToast(data.message);
                        window.location.reload();
                    } else {
                        showFlashToast(data.message || 'Ошибка при отправке отзыва');
                    }
                } catch {
                    showFlashToast('Ошибка соединения');
                } finally {
                    btn.disabled = false;
                    btn.textContent = 'Отправить';
                }
            });
        });
    </script>
@endpush
