@extends('layouts.main')

@section('content')

    {{-- Хлебные крошки --}}
    <x-breadcrumbs :items="$breadcrumbs" />

    <!-- Page title -->
    <h1 class="h3 container mb-4">{{ $category->name }}</h1>

    <!-- Selected filters + Sorting -->
    <section class="container mb-4">
        <div class="row">
            <div class="col-lg-9">
                <div class="d-md-flex align-items-start">
                    <div class="h6 fs-sm fw-normal text-nowrap translate-middle-y mt-3 mb-0 me-4">
                        Всего <span class="fw-semibold">{{ $totalProducts }}</span> товар(-ов)
                    </div>
                    <div class="d-flex flex-wrap gap-2">

                        {{-- Статусы --}}
                        @if($selectedStatuses = Arr::wrap(request('status')))
                            @foreach($selectedStatuses as $status)
                                @php
                                    if(is_array($status)) $status = $status['title'] ?? implode(', ', $status);
                                    $query = request()->except('page');
                                    $query['status'] = array_values(array_filter($selectedStatuses, fn($s) => (is_array($s) ? ($s['title'] ?? '') : $s) !== $status));
                                @endphp
                                <a href="{{ url()->current() . '?' . http_build_query($query) }}" class="btn btn-sm btn-secondary">
                                    <i class="ci-close fs-sm ms-n1 me-1"></i>
                                    {{ $status }}
                                </a>
                            @endforeach
                        @endif

                        {{-- Бренды --}}
                        @if($selectedBrands = Arr::wrap(request('brand')))
                            @foreach($selectedBrands as $brand)
                                @php
                                    if(is_array($brand)) $brand = $brand['title'] ?? implode(', ', $brand);
                                    $query = request()->except('page');
                                    $query['brand'] = array_values(array_filter($selectedBrands, fn($b) => (is_array($b) ? ($b['title'] ?? '') : $b) !== $brand));
                                @endphp
                                <a href="{{ url()->current() . '?' . http_build_query($query) }}" class="btn btn-sm btn-secondary">
                                    <i class="ci-close fs-sm ms-n1 me-1"></i>
                                    {{ $brand }}
                                </a>
                            @endforeach
                        @endif

                        {{-- Опции --}}
                        @if($selectedOptions = Arr::wrap(request('option')))
                            @foreach($selectedOptions as $option)
                                @php
                                    if(is_array($option)) $option = $option['title'] ?? implode(', ', $option);
                                    $query = request()->except('page');
                                    $query['option'] = array_values(array_filter($selectedOptions, fn($o) => (is_array($o) ? ($o['title'] ?? '') : $o) !== $option));
                                @endphp
                                <a href="{{ url()->current() . '?' . http_build_query($query) }}" class="btn btn-sm btn-secondary">
                                    <i class="ci-close fs-sm ms-n1 me-1"></i>
                                    {{ $option }}
                                </a>
                            @endforeach
                        @endif

                        {{-- Диапазон цен --}}
                        @if(request('price_min') || request('price_max'))
                            @php
                                $priceText = '$' . (request('price_min') ?? '0') . ' - $' . (request('price_max') ?? '∞');
                                $query = request()->query();
                                unset($query['price_min'], $query['price_max']);
                            @endphp
                            <a href="{{ url()->current() . '?' . http_build_query($query) }}" class="btn btn-sm btn-secondary">
                                <i class="ci-close fs-sm ms-n1 me-1"></i>
                                {{ $priceText }}
                            </a>
                        @endif

                        {{-- Очистить все --}}
                        @if(request()->query())
                            <a href="{{ route('catalog.category', $category->slug) }}" class="btn btn-sm btn-secondary bg-transparent border-0 text-decoration-underline px-0 ms-2">
                                Очистить все
                            </a>
                        @endif

                    </div>
                </div>
            </div>
            <div class="col-lg-3 mt-3 mt-lg-0">
                <form method="GET" class="d-flex align-items-center justify-content-lg-end text-nowrap">
                    <label class="form-label fw-semibold mb-0 me-2">Сортировать:</label>
                    <div style="width: 190px">
                        <select name="sort" class="form-select border-0 rounded-0 px-1" onchange="this.form.submit()">
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Сначала дешевле</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Сначала дороже</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Название: А-Я</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Название: Я-А</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>По новизне</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <hr class="d-lg-none my-3">
    </section>

    <!-- Products grid + Sidebar -->
    <section class="container pb-5 mb-sm-2 mb-md-3 mb-lg-4 mb-xl-5">
        <div class="row">

            <!-- Filter sidebar -->
            <aside class="col-lg-3">
                <div class="offcanvas-lg offcanvas-start" id="filterSidebar">
                    <div class="offcanvas-header py-3">
                        <h5 class="offcanvas-title">Фильтры</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body flex-column pt-2 py-lg-0">

                        <!-- Status filter -->
                        @if(!empty($allFlags) && $allFlags->count())
                            <div class="w-100 border rounded p-3 p-xl-4 mb-3 mb-xl-4">
                                <h4 class="h6">Статус</h4>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($allFlags as $flag)
                                        @php
                                            $flagTitle = is_array($flag) ? ($flag['title'] ?? implode(', ', $flag)) : $flag;
                                            $selectedStatuses = Arr::wrap(request('status'));
                                            $isSelected = in_array($flagTitle, $selectedStatuses);
                                            $query = request()->query();
                                            if ($isSelected) {
                                                $query['status'] = array_values(array_filter($selectedStatuses, fn($s) => (is_array($s) ? ($s['title'] ?? '') : $s) !== $flagTitle));
                                            } else {
                                                $query['status'] = array_merge($selectedStatuses, [$flagTitle]);
                                            }
                                        @endphp
                                        <a href="{{ url()->current() . '?' . http_build_query($query) }}"
                                           class="btn btn-sm {{ $isSelected ? 'btn-primary' : 'btn-outline-secondary' }}">
                                            {{ $flagTitle }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Подразделы -->
                        @if(!empty($leafCategories) && $leafCategories->count())
                            <div class="w-100 border rounded p-3 p-xl-4 mb-3 mb-xl-4">
                                <h4 class="h6 mb-2">Подразделы каталога</h4>
                                <ul class="list-unstyled d-block m-0">
                                    @foreach($leafCategories as $cat)
                                        <li class="nav d-block pt-2 mt-1">
                                            <a class="nav-link animate-underline fw-normal p-0"
                                               href="{{ route('catalog.category', $cat['slug']) }}">
                                                <span class="animate-target text-truncate me-3">{{ $cat['name'] }}</span>
                                                <span class="text-body-secondary fs-xs ms-auto">{{ $cat['products_count'] }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Диапазон цен -->
                        <div class="w-100 border rounded p-3 p-xl-4 mb-3 mb-xl-4">
                            <h4 class="h6 mb-2" id="slider-label">Стоимость</h4>
                            <div class="range-slider" aria-labelledby="slider-label"
                                 data-range-slider='{
                                    "startMin": {{ $priceRange->min_price ?? 0 }},
                                    "startMax": {{ $priceRange->max_price ?? 1000 }},
                                    "min": {{ $priceRange->min_price ?? 0 }},
                                    "max": {{ $priceRange->max_price ?? 1000 }},
                                    "step": 1,
                                    "tooltipPostfix": "$"
                                 }'>
                                <div class="range-slider-ui"></div>
                                <div class="d-flex align-items-center mt-2">
                                    <div class="position-relative w-50">
                                        <input type="number" class="form-control form-icon-end" min="0"
                                               data-range-slider-min
                                               value="{{ request('price_min', $priceRange->min_price ?? 0) }}">
                                        <span class="position-absolute top-50 end-0 translate-middle-y me-3"> р.</span>
                                    </div>
                                    <i class="ci-minus text-body-emphasis mx-2"></i>
                                    <div class="position-relative w-50">
                                        <input type="number" class="form-control form-icon-end" min="0"
                                               data-range-slider-max
                                               value="{{ request('price_max', $priceRange->max_price ?? 1000) }}">
                                        <span class="position-absolute top-50 end-0 translate-middle-y me-3"> р.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </aside>

            <!-- Product grid -->
            <div id="products-container" class="col-lg-9">
                @include('catalog.partials.products', ['products' => $products])
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('products-container');
            let priceSlider = null;

            function getFilters() {
                let params = new URLSearchParams();

                // Сортировка
                let sort = document.querySelector('[name="sort"]')?.value;
                if (sort) params.append('sort', sort);

                // Статусы, бренды, опции — берём из URL query
                let queryParams = new URLSearchParams(window.location.search);
                queryParams.forEach((v,k) => {
                    if(['status','brand','option'].includes(k)) {
                        if(Array.isArray(v)) v.forEach(val=>params.append(k+'[]', val));
                        else params.append(k+'[]', v);
                    }
                });

                // Диапазон цен
                if(priceSlider){
                    let values = priceSlider.get();
                    params.set('price_min', values[0]);
                    params.set('price_max', values[1]);
                }

                return params.toString();
            }

            function loadProducts(url = null){
                const baseUrl = "{{ route('catalog.filter', $category->slug) }}";
                const query = getFilters();
                fetch((url || baseUrl) + '?' + query)
                    .then(res => res.text())
                    .then(html => {
                        container.innerHTML = html;
                    });
            }

            // Инициализация слайдера
            function initPriceSlider() {
                const wrapper = document.querySelector('.range-slider');
                if (!wrapper) return;

                const sliderElement = wrapper.querySelector('.range-slider-ui');
                const minInput = wrapper.querySelector('[data-range-slider-min]');
                const maxInput = wrapper.querySelector('[data-range-slider-max]');
                const config = JSON.parse(wrapper.dataset.rangeSlider);

                if(sliderElement.noUiSlider) sliderElement.noUiSlider.destroy();

                noUiSlider.create(sliderElement, {
                    start: [minInput.value || config.startMin, maxInput.value || config.startMax],
                    connect: true,
                    range: { min: parseInt(config.min), max: parseInt(config.max) },
                    step: parseInt(config.step),
                    format: { to: v => parseInt(v,10), from: v => Number(v) }
                });

                priceSlider = sliderElement.noUiSlider;

                priceSlider.on('change', function(values){
                    minInput.value = values[0];
                    maxInput.value = values[1];
                    loadProducts();
                });

                minInput.addEventListener('change', () => {
                    priceSlider.set([minInput.value, null]);
                    loadProducts();
                });

                maxInput.addEventListener('change', () => {
                    priceSlider.set([null, maxInput.value]);
                    loadProducts();
                });
            }

            initPriceSlider();
        });
    </script>
@endpush
