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
                    <div class="h6 fs-sm fw-normal text-nowrap translate-middle-y mt-3 mb-0 me-4">Found <span class="fw-semibold">732</span> items</div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-sm btn-secondary">
                            <i class="ci-close fs-sm ms-n1 me-1"></i>
                            Sale
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary">
                            <i class="ci-close fs-sm ms-n1 me-1"></i>
                            Asus
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary">
                            <i class="ci-close fs-sm ms-n1 me-1"></i>
                            1 TB
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary">
                            <i class="ci-close fs-sm ms-n1 me-1"></i>
                            $340 - $1,250
                        </button>
                        @if(request('status'))
                            <a href="{{ route('catalog.category', $category->slug) }}"
                               class="btn btn-sm btn-secondary bg-transparent border-0 text-decoration-underline px-0 ms-2">
                                Очистить
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


    <!-- Products grid + Sidebar with filters -->
    <section class="container pb-5 mb-sm-2 mb-md-3 mb-lg-4 mb-xl-5">
        <div class="row">

            <!-- Filter sidebar that turns into offcanvas on screens < 992px wide (lg breakpoint) -->
            <aside class="col-lg-3">
                <div class="offcanvas-lg offcanvas-start" id="filterSidebar">
                    <div class="offcanvas-header py-3">
                        <h5 class="offcanvas-title">Фильтры</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#filterSidebar" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body flex-column pt-2 py-lg-0">

                        <!-- Status -->
                        <div class="w-100 border rounded p-3 p-xl-4 mb-3 mb-xl-4">
                            <h4 class="h6">Статус</h4>

                            <div class="d-flex flex-wrap gap-2">
                                @foreach($allFlags as $flag)
                                    <a href="{{ request()->fullUrlWithQuery(['status' => $flag]) }}"
                                       class="btn btn-sm {{ request('status') == $flag ? 'btn-primary' : 'btn-outline-secondary' }}">

                                        {{ $flag }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="w-100 border rounded p-3 p-xl-4 mb-3 mb-xl-4">
                            <h4 class="h6 mb-2">Разделы каталога</h4>

                            <ul class="list-unstyled d-block m-0">
                                @foreach($leafCategories as $cat)
                                    <li class="nav d-block pt-2 mt-1">
                                        <a class="nav-link animate-underline fw-normal p-0"
                                           href="{{ route('catalog.category', $cat['slug']) }}">

                                            <span class="animate-target text-truncate me-3">
                                                {{ $cat['name'] }}
                                            </span>

                                            <span class="text-body-secondary fs-xs ms-auto">
                                                {{ $cat['products_count'] }}
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>


                        <!-- Price range -->
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

                        <!-- Brand (checkboxes) -->
                        <div class="w-100 border rounded p-3 p-xl-4 mb-3 mb-xl-4">
                            <h4 class="h6">Brand</h4>
                            <div class="d-flex flex-column gap-1">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="apple" checked>
                                        <label for="apple" class="form-check-label text-body-emphasis">Apple</label>
                                    </div>
                                    <span class="text-body-secondary fs-xs">64</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="asus">
                                        <label for="asus" class="form-check-label text-body-emphasis">Asus</label>
                                    </div>
                                    <span class="text-body-secondary fs-xs">310</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="bao">
                                        <label for="bao" class="form-check-label text-body-emphasis">Bang &amp; Olufsen</label>
                                    </div>
                                    <span class="text-body-secondary fs-xs">47</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="bosh">
                                        <label for="bosh" class="form-check-label text-body-emphasis">Bosh</label>
                                    </div>
                                    <span class="text-body-secondary fs-xs">112</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="cobra">
                                        <label for="cobra" class="form-check-label text-body-emphasis">Cobra</label>
                                    </div>
                                    <span class="text-body-secondary fs-xs">96</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="dell">
                                        <label for="dell" class="form-check-label text-body-emphasis">Dell</label>
                                    </div>
                                    <span class="text-body-secondary fs-xs">178</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="foxconn">
                                        <label for="foxconn" class="form-check-label text-body-emphasis">Foxconn</label>
                                    </div>
                                    <span class="text-body-secondary fs-xs">95</span>
                                </div>
                                <div class="accordion mb-n2">
                                    <div class="accordion-item border-0">
                                        <div class="accordion-collapse collapse" id="more-brands">
                                            <div class="d-flex flex-column gap-1">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="hp">
                                                        <label for="hp" class="form-check-label text-body-emphasis">Hewlett Packard</label>
                                                    </div>
                                                    <span class="text-body-secondary fs-xs">61</span>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="huawei">
                                                        <label for="huawei" class="form-check-label text-body-emphasis">Huawei</label>
                                                    </div>
                                                    <span class="text-body-secondary fs-xs">417</span>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="panasonic">
                                                        <label for="panasonic" class="form-check-label text-body-emphasis">Panasonic</label>
                                                    </div>
                                                    <span class="text-body-secondary fs-xs">123</span>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="samsung">
                                                        <label for="samsung" class="form-check-label text-body-emphasis">Samsung</label>
                                                    </div>
                                                    <span class="text-body-secondary fs-xs">284</span>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="sony">
                                                        <label for="sony" class="form-check-label text-body-emphasis">Sony</label>
                                                    </div>
                                                    <span class="text-body-secondary fs-xs">133</span>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="toshiba">
                                                        <label for="toshiba" class="form-check-label text-body-emphasis">Toshiba</label>
                                                    </div>
                                                    <span class="text-body-secondary fs-xs">39</span>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="xiaomi">
                                                        <label for="xiaomi" class="form-check-label text-body-emphasis">Xiaomi</label>
                                                    </div>
                                                    <span class="text-body-secondary fs-xs">421</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-header">
                                            <button type="button" class="accordion-button w-auto fs-sm fw-medium collapsed animate-underline py-2" data-bs-toggle="collapse" data-bs-target="#more-brands" aria-expanded="false" aria-controls="more-brands" aria-label="Show/hide more brands">
                                                <span class="animate-target me-2" data-label-collapsed="Show all" data-label-expanded="Show less"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SSD size (checkboxes) -->
                        <div class="w-100 border rounded p-3 p-xl-4 mb-3 mb-xl-4">
                            <h4 class="h6">SSD size</h4>
                            <div class="d-flex flex-column gap-1">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="tb-2">
                                        <label for="tb-2" class="form-check-label text-body-emphasis">2 TB</label>
                                    </div>
                                    <span class="text-body-secondary fs-xs">13</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="tb-1">
                                        <label for="tb-1" class="form-check-label text-body-emphasis">1 TB</label>
                                    </div>
                                    <span class="text-body-secondary fs-xs">28</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="gb-512" checked>
                                        <label for="gb-512" class="form-check-label text-body-emphasis">512 GB</label>
                                    </div>
                                    <span class="text-body-secondary fs-xs">47</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="gb-256">
                                        <label for="gb-256" class="form-check-label text-body-emphasis">256 GB</label>
                                    </div>
                                    <span class="text-body-secondary fs-xs">56</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="gb-128">
                                        <label for="gb-128" class="form-check-label text-body-emphasis">128 GB</label>
                                    </div>
                                    <span class="text-body-secondary fs-xs">69</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="gb-64">
                                        <label for="gb-64" class="form-check-label text-body-emphasis">64 GB or less</label>
                                    </div>
                                    <span class="text-body-secondary fs-xs">141</span>
                                </div>
                            </div>
                        </div>

                        <!-- Color -->
                        <div class="w-100 border rounded p-3 p-xl-4">
                            <h4 class="h6">Color</h4>
                            <div class="nav d-block mt-n2">
                                <button type="button" class="nav-link w-auto animate-underline fw-normal pt-2 pb-0 px-0">
                                    <span class="rounded-circle me-2" style="width: .875rem; height: .875rem; margin-top: .125rem; background-color: #8bc4ab"></span>
                                    <span class="animate-target">Green</span>
                                </button>
                                <button type="button" class="nav-link w-auto animate-underline fw-normal mt-1 pt-2 pb-0 px-0">
                                    <span class="rounded-circle me-2" style="width: .875rem; height: .875rem; margin-top: .125rem; background-color: #ee7976"></span>
                                    <span class="animate-target">Coral red</span>
                                </button>
                                <button type="button" class="nav-link w-auto animate-underline fw-normal mt-1 pt-2 pb-0 px-0">
                                    <span class="rounded-circle me-2" style="width: .875rem; height: .875rem; margin-top: .125rem; background-color: #df8fbf"></span>
                                    <span class="animate-target">Light pink</span>
                                </button>
                                <button type="button" class="nav-link w-auto animate-underline fw-normal mt-1 pt-2 pb-0 px-0">
                                    <span class="rounded-circle me-2" style="width: .875rem; height: .875rem; margin-top: .125rem; background-color: #9acbf1"></span>
                                    <span class="animate-target">Sky blue</span>
                                </button>
                                <button type="button" class="nav-link w-auto animate-underline fw-normal mt-1 pt-2 pb-0 px-0">
                                    <span class="rounded-circle me-2" style="width: .875rem; height: .875rem; margin-top: .125rem; background-color: #364254"></span>
                                    <span class="animate-target">Black</span>
                                </button>
                                <button type="button" class="nav-link w-auto animate-underline fw-normal mt-1 pt-2 pb-0 px-0">
                                    <span class="border rounded-circle me-2" style="width: .875rem; height: .875rem; margin-top: .125rem; background-color: #ffffff"></span>
                                    <span class="animate-target">White</span>
                                </button>
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

            function getFilters() {
                let params = new URLSearchParams();

                // Статусы
                document.querySelectorAll('input[name="status[]"]:checked')
                    .forEach(el => params.append('status[]', el.value));

                // Сортировка
                let sort = document.querySelector('[name="sort"]')?.value;
                if (sort) params.append('sort', sort);

                // Цена
                const priceMinInput = document.querySelector('[data-range-slider-min]');
                const priceMaxInput = document.querySelector('[data-range-slider-max]');
                if (priceMinInput?.value) params.append('price_min', priceMinInput.value);
                if (priceMaxInput?.value) params.append('price_max', priceMaxInput.value);

                return params.toString();
            }

            function loadProducts(url = null) {
                const baseUrl = "{{ route('catalog.filter', $category->slug) }}";
                const query = getFilters();

                fetch((url || baseUrl) + '?' + query)
                    .then(res => res.text())
                    .then(html => {
                        container.innerHTML = html;
                        attachPagination();
                    });
            }

            // Автофильтр статусов
            document.querySelectorAll('input[name="status[]"]').forEach(el => {
                el.addEventListener('change', () => loadProducts());
            });

            // Сортировка
            document.querySelector('[name="sort"]')?.addEventListener('change', () => loadProducts());

            // Ползунок цены
            const priceSlider = document.querySelector('.range-slider');
            if (priceSlider) {
                const config = JSON.parse(priceSlider.dataset.rangeSlider);
                const sliderUi = priceSlider.querySelector('.range-slider-ui');
                const minInput = priceSlider.querySelector('[data-range-slider-min]');
                const maxInput = priceSlider.querySelector('[data-range-slider-max]');

                // Initialize noUiSlider
                noUiSlider.create(sliderUi, {
                    start: [config.startMin, config.startMax],
                    connect: true,
                    range: {
                        'min': config.min,
                        'max': config.max
                    },
                    step: config.step,
                    tooltips: {
                        to: function (value) {
                            return Math.round(value) + config.tooltipPostfix;
                        },
                        from: function (value) {
                            return Number(value.replace(config.tooltipPostfix, ''));
                        }
                    }
                });

                // On slider update, sync inputs and load products
                sliderUi.noUiSlider.on('update', function (values, handle) {
                    const min = Math.round(values[0]);
                    const max = Math.round(values[1]);
                    minInput.value = min;
                    maxInput.value = max;
                    loadProducts();
                });

                // On input change, sync slider and load products
                minInput.addEventListener('input', function () {
                    sliderUi.noUiSlider.set([this.value, null]);
                });
                maxInput.addEventListener('input', function () {
                    sliderUi.noUiSlider.set([null, this.value]);
                });
            }

            // AJAX пагинация
            function attachPagination() {
                document.querySelectorAll('#products-container .pagination a')
                    .forEach(link => {
                        link.addEventListener('click', function (e) {
                            e.preventDefault();
                            loadProducts(this.href);
                        });
                    });
            }

            attachPagination();
        });
    </script>
@endpush
