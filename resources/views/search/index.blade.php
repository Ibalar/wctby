@extends('layouts.main')

@section('title', 'Поиск: ' . $query)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="h2 mb-4">
                @if($query)
                    Результаты поиска: "{{ $query }}"
                @else
                    Поиск по каталогу
                @endif
            </h1>

            <form action="{{ route('search') }}" method="GET" class="mb-5">
                <div class="input-group input-group-lg">
                    <input 
                        type="text" 
                        name="q" 
                        class="form-control" 
                        placeholder="Введите название товара, артикул или описание..."
                        value="{{ $query }}"
                        autofocus
                    >
                    <button class="btn btn-primary" type="submit">
                        <i class="ci-search me-2"></i>Найти
                    </button>
                </div>
            </form>

            @if($query && mb_strlen($query) < 2)
                <div class="alert alert-warning">
                    <i class="ci-alert-triangle me-2"></i>
                    Введите минимум 2 символа для поиска
                </div>
            @elseif($total > 0)
                <p class="text-muted mb-4">Найдено товаров: {{ $total }}</p>

                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                <div class="mt-5">
                    {{ $products->links() }}
                </div>
            @elseif($query)
                <div class="alert alert-info">
                    <i class="ci-info-circle me-2"></i>
                    По запросу "{{ $query }}" ничего не найдено. Попробуйте изменить запрос.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
