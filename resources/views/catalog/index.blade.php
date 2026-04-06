@extends('layouts.main')

@section('title', 'Каталог')

@section('content')
    <section class="container pt-4 pb-5 mb-2 mb-md-3 mb-lg-4 mb-xl-5">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div class="mb-3 mb-sm-0">
                <h1 class="h3 mb-2">Каталог</h1>
                <p class="text-body-secondary mb-0">Основные категории и быстрые переходы по разделам магазина.</p>
            </div>
            <a class="btn btn-lg btn-outline-secondary" href="{{ route('home') }}">
                <i class="ci-chevron-left fs-lg ms-n1 me-2"></i>
                На главную
            </a>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 gy-5">
            @foreach($categories as $category)
                @php
                    $imageUrl = $category->catalog_image_url;

                    if (!$imageUrl && $category->getFirstMediaUrl('images')) {
                        $imageUrl = $category->getFirstMediaUrl('images');
                    }

                    if (!$imageUrl && !empty($category->promo_data['image'])) {
                        $imageUrl = $category->promo_data['image'];
                    }

                    if (!$imageUrl) {
                        $childWithImage = $category->children->first(function ($child) {
                            return $child->getFirstMediaUrl('images');
                        });
                        $imageUrl = $childWithImage?->getFirstMediaUrl('images');
                    }

                    $imageUrl = $imageUrl ?: asset('assets/img/placeholder.jpg');
                    $childLinks = $category->children->take(6);
                @endphp

                <div class="col">
                    <div class="hover-effect-scale h-100">
                        <a class="d-block bg-body-tertiary rounded p-4 mb-4" href="{{ route('catalog.category', $category->slug) }}">
                            <div class="ratio" style="--cz-aspect-ratio: calc(184 / 258 * 100%)">
                                <img src="{{ $imageUrl }}" class="hover-effect-target object-fit-contain" alt="{{ $category->name }}">
                            </div>
                        </a>

                        <div class="d-flex align-items-start justify-content-between gap-3 pb-2 mb-1">
                            <h2 class="h6 d-flex w-100 mb-0">
                                <a class="animate-underline animate-target d-inline text-truncate" href="{{ route('catalog.category', $category->slug) }}">
                                    {{ $category->name }}
                                </a>
                            </h2>
                            @if(($category->direct_products_count ?? 0) > 0)
                                <span class="badge text-bg-secondary flex-shrink-0">{{ $category->direct_products_count }}</span>
                            @endif
                        </div>

                        @if($childLinks->isNotEmpty())
                            <ul class="nav flex-column gap-2 mt-n1">
                                @foreach($childLinks as $child)
                                    <li class="d-flex w-100 pt-1">
                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="{{ route('catalog.category', $child->slug) }}">
                                            {{ $child->name }}
                                        </a>
                                        @if(($child->direct_products_count ?? 0) > 0)
                                            <span class="text-body-secondary fs-xs ms-auto">{{ $child->direct_products_count }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @elseif($category->description)
                            <p class="text-body-secondary fs-sm mb-0">{{ \Illuminate\Support\Str::limit(strip_tags($category->description), 110) }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    @if($featuredProducts->count())
        <section class="container pb-5 mb-sm-2 mb-md-3 mb-lg-4 mb-xl-5">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="h3 mb-1">Рекомендуемые товары</h2>
                </div>
            </div>

            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif
@endsection
