@extends('layouts.main')

@section('title', $bundle->name)

@section('content')
    <section class="container pt-5 mt-2 mt-sm-3 mt-lg-4">
        <x-breadcrumbs :items="[['label' => 'Главная', 'url' => route('home')], ['label' => 'Комплекты', 'url' => route('bundles.index')], ['label' => $bundle->name, 'url' => null]]" />

        <div class="row">
            <div class="col-md-6 mb-4">
                @if($bundle->getFirstMediaUrl('images'))
                    <img src="{{ $bundle->getFirstMediaUrl('images') }}" class="img-fluid rounded-4" alt="{{ $bundle->name }}">
                @endif
            </div>

            <div class="col-md-6">
                <h1 class="h2 mb-3">{{ $bundle->name }}</h1>

                @if($bundle->total_price)
                    <p class="h3 text-primary fw-bold mb-3">{{ number_format($bundle->total_price, 2) }} BYN</p>
                @endif

                @if($bundle->description)
                    <div class="mb-4">{!! $bundle->description !!}</div>
                @endif

                <h5 class="mb-3">Состав комплекта:</h5>
                <ul class="list-group mb-4">
                    @foreach($bundle->items as $item)
                        <li class="list-group-item d-flex align-items-center">
                            @if($item->product && $item->product->getFirstMediaUrl('images'))
                                <img src="{{ $item->product->getFirstMediaUrl('images') }}" width="60" class="me-3 rounded" alt="">
                            @endif
                            <div>
                                <a href="{{ route('catalog.product', $item->product->slug) }}" class="fw-medium">
                                    {{ $item->product->name ?? 'Товар #' . $item->product_id }}
                                </a>
                                @if($item->sku)
                                    <span class="text-body-tertiary">({{ $item->sku->sku }})</span>
                                @endif
                                <span class="text-body-tertiary"> — {{ $item->quantity }} шт.</span>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <form data-add-to-cart method="POST" action="{{ route('cart.add') }}">
                    @csrf
                    <input type="hidden" name="purchasable_type" value="bundle">
                    <input type="hidden" name="purchasable_id" value="{{ $bundle->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="ci-cart fs-lg me-2"></i>В корзину
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
