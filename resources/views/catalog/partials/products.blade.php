
@if($products->isEmpty())
    <div class="alert alert-info">В этой категории пока нет товаров</div>
@else
    <div class="row row-cols-2 row-cols-md-3 g-4 pb-3 mb-3">
        @foreach($products as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>

    @if($products->hasPages())
        <nav class="d-flex justify-content-center" aria-label="Pagination">
            {{ $products->links() }}
        </nav>
    @endif
@endif

