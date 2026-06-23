@extends('layouts.main')

@section('title', 'Комплекты товаров')

@section('content')
    <section class="container pt-5 mt-2 mt-sm-3 mt-lg-4">
        <x-breadcrumbs :items="[['label' => 'Главная', 'url' => route('home')], ['label' => 'Комплекты', 'url' => null]]" />

        <h1 class="h2 mb-4">Комплекты товаров</h1>

        @if($bundles->isEmpty())
            <p class="text-body-tertiary">Пока нет доступных комплектов.</p>
        @else
            <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-4">
                @foreach($bundles as $bundle)
                    <div class="col">
                        <div class="card h-100">
                            @if($bundle->getFirstMediaUrl('images'))
                                <img src="{{ $bundle->getFirstMediaUrl('images') }}" class="card-img-top" alt="{{ $bundle->name }}">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ route('bundles.show', $bundle->slug) }}" class="text-decoration-none stretched-link">
                                        {{ $bundle->name }}
                                    </a>
                                </h5>
                                @if($bundle->total_price)
                                    <p class="text-primary fw-bold mb-0">{{ number_format($bundle->total_price, 2) }} BYN</p>
                                @endif
                                <p class="text-body-tertiary fs-sm mb-0">{{ $bundle->items->count() }} товаров</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $bundles->links() }}
            </div>
        @endif
    </section>
@endsection
