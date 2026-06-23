@extends('layouts.main')

@section('title', 'Избранное')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="h2 mb-4">
                <i class="ci-heart me-2"></i>Избранное
            </h1>

            @if($total > 0)
                <p class="text-muted mb-4">Товаров в избранном: {{ $total }}</p>

                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                    @foreach($wishlistItems as $item)
                        @php
                            $product = $item->product;
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
                        @endphp

                        <div class="col" data-wishlist-item="{{ $item->id }}">
                            <div class="product-card animate-underline hover-effect-opacity bg-body rounded position-relative">
                                <button 
                                    type="button" 
                                    class="btn btn-icon btn-sm btn-danger position-absolute top-0 end-0 z-3 m-2 wishlist-remove-btn"
                                    data-wishlist-id="{{ $item->id }}"
                                    aria-label="Удалить из избранного"
                                >
                                    <i class="ci-x fs-sm"></i>
                                </button>

                                <a class="d-block rounded-top overflow-hidden p-3 p-sm-4" href="{{ route('catalog.product', $product->slug) }}">
                                    <div class="ratio" style="--cz-aspect-ratio: calc(240 / 258 * 100%)">
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                                    </div>
                                </a>

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
                                            <input type="hidden" name="purchasable_type" value="{{ $firstSku ? 'sku' : 'product' }}">
                                            <input type="hidden" name="purchasable_id" value="{{ $firstSku?->id ?? $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-icon btn-primary animate-slide-end" aria-label="Добавить в корзину">
                                                <i class="ci-shopping-cart fs-base animate-target"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5">
                    {{ $wishlistItems->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="ci-info-circle me-2"></i>
                    В избранном пока нет товаров. Добавляйте товары, нажимая на сердечко в карточках товаров.
                </div>

                <a href="{{ route('catalog.index') }}" class="btn btn-primary">
                    <i class="ci-shopping-bag me-2"></i>Перейти в каталог
                </a>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.wishlist-remove-btn').forEach(function(btn) {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();

            const wishlistId = this.dataset.wishlistId;
            const itemElement = document.querySelector(`[data-wishlist-item="${wishlistId}"]`);

            if (!confirm('Удалить товар из избранного?')) {
                return;
            }

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const response = await fetch(`/wishlist/${wishlistId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json();

                if (response.ok) {
                    itemElement.remove();
                    
                    document.querySelectorAll('[data-wishlist-count]').forEach(function(el) {
                        el.textContent = data.count;
                    });

                    if (data.count === 0) {
                        location.reload();
                    }
                } else {
                    alert('Ошибка при удалении товара');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ошибка при удалении товара');
            }
        });
    });
});
</script>
@endpush
@endsection
