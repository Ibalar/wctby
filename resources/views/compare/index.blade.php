@extends('layouts.main')

@section('title', 'Сравнение товаров')

@section('content')
    <section class="container pt-5 mt-2">
        <x-breadcrumbs :items="[['label' => 'Главная', 'url' => route('home')], ['label' => 'Сравнение', 'url' => null]]" />

        <h1 class="h2 mb-4">Сравнение товаров</h1>

        @if($products->isEmpty())
            <p class="text-body-tertiary">Нет товаров для сравнения.</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary">Перейти в каталог</a>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th style="width:200px">Характеристика</th>
                            @foreach($products as $product)
                                <th class="text-center">
                                    <a href="{{ route('catalog.product', $product->slug) }}">{{ $product->name }}</a>
                                    <br>
                                    <a href="{{ route('compare.remove', $product->id) }}" class="btn btn-sm btn-outline-danger mt-1">×</a>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Фото</td>
                            @foreach($products as $product)
                                <td class="text-center">
                                    @if($product->getFirstMediaUrl('images'))
                                        <img src="{{ $product->getFirstMediaUrl('images') }}" width="120" alt="">
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Цена</td>
                            @foreach($products as $product)
                                <td class="text-center fw-bold">{{ number_format($product->base_price, 2) }} BYN</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Категория</td>
                            @foreach($products as $product)
                                <td class="text-center">{{ $product->category?->name ?? '-' }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Артикул</td>
                            @foreach($products as $product)
                                <td class="text-center">{{ $product->sku }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Описание</td>
                            @foreach($products as $product)
                                <td>{{ $product->short_description ?: Str::limit(strip_tags($product->description ?? ''), 200) }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Характеристики</td>
                            @foreach($products as $product)
                                <td>
                                    @if($product->properties)
                                        @foreach($product->properties as $key => $value)
                                            <strong>{{ $key }}:</strong> {{ $value }}<br>
                                        @endforeach
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
