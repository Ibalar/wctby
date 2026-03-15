@props(['categories' => []])

@if(count($categories) > 0)
    <!-- Categories mega menu -->
    <div class="col-lg-3">
        <div class="navbar-nav">
            <div class="dropdown w-100">

                <!-- Buttton visible on screens > 991px wide (lg breakpoint) -->
                <div class="cursor-pointer d-none d-lg-block" data-bs-toggle="dropdown" data-bs-trigger="hover" data-bs-theme="dark">
                    <a class="position-absolute top-0 start-0 w-100 h-100" href="{{ route('catalog.index') }}">
                        <span class="visually-hidden">Категории</span>
                    </a>
                    <button type="button" class="btn btn-lg btn-secondary br-none dropdown-toggle w-100 rounded-bottom-0 justify-content-start pe-none">
                        <i class="ci-grid fs-lg"></i>
                        <span class="ms-2 me-auto">Категории</span>
                    </button>
                </div>

                <!-- Buttton visible on screens < 992px wide (lg breakpoint) -->
                <button type="button" class="btn btn-lg btn-secondary dropdown-toggle w-100 justify-content-start d-lg-none mb-2" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                    <i class="ci-grid fs-lg"></i>
                    <span class="ms-2 me-auto">Каталог</span>
                </button>

                <!-- Mega menu -->
                <ul class="dropdown-menu {{ request()->routeIs('home') ? 'dropdown-menu-static' : '' }} w-100 rounded-top-0 rounded-bottom-4 py-1 p-lg-1" style="--cz-dropdown-spacer: 0; --cz-dropdown-item-padding-y: .625rem; --cz-dropdown-item-spacer: 0">

                    <!-- All Categories link for mobile -->
                    <li class="d-lg-none pt-2">
                        <a class="dropdown-item fw-medium" href="{{ route('catalog.index') }}">
                            <i class="ci-grid fs-xl opacity-60 pe-1 me-2"></i>
                            Все категории
                            <i class="ci-chevron-right fs-base ms-auto me-n1"></i>
                        </a>
                    </li>

                    <!-- Loop through parent categories (categories with no parent) -->
                    @foreach($categories->whereNull('parent_id') as $category)
                        @if($category->children->count() > 0)
                            <!-- Category with children -->
                            <li class="dropend position-static">
                                <div class="position-relative rounded pt-2 pb-1 px-lg-2" data-bs-toggle="dropdown" data-bs-trigger="hover">
                                    <a class="dropdown-item fw-medium stretched-link d-none d-lg-flex" href="{{ route('catalog.category', $category->slug) }}">
                                        <i class="ci-folder fs-xl opacity-60 pe-1 me-2"></i>
                                        <span class="text-truncate">{{ $category->name }}</span>
                                        <i class="ci-chevron-right fs-base ms-auto me-n1"></i>
                                    </a>
                                    <div class="dropdown-item fw-medium text-wrap stretched-link d-lg-none">
                                        <i class="ci-folder fs-xl opacity-60 pe-1 me-2"></i>
                                        {{ $category->name }}
                                        <i class="ci-chevron-down fs-base ms-auto me-n1"></i>
                                    </div>
                                </div>

                                <!-- Subcategories mega menu -->
                                <div class="dropdown-menu rounded-4 p-4" style="top: 1rem; height: calc(100% - .1875rem); --cz-dropdown-spacer: .3125rem; animation: none;">
                                    <div class="d-flex flex-column flex-lg-row h-100 gap-4">

                                        <!-- First column of subcategories -->
                                        <div style="min-width: 194px">
                                            <div class="d-flex w-100">
                                                <a class="animate-underline animate-target d-inline h6 text-dark-emphasis text-decoration-none text-truncate" href="{{ route('catalog.category', $category->slug) }}">
                                                    {{ $category->name }}
                                                </a>
                                            </div>

                                            <!-- Display children categories -->
                                            <ul class="nav flex-column gap-2 mt-n2">
                                                @foreach($category->children->take(10) as $child)
                                                    <li class="d-flex w-100 pt-1">
                                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="{{ route('catalog.category', $child->slug) }}">
                                                            {{ $child->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>

                                        <!-- Second column if there are more than 10 subcategories -->
                                        @if($category->children->count() > 10)
                                            <div style="min-width: 194px">
                                                <ul class="nav flex-column gap-2 mt-2 mt-lg-0">
                                                    @foreach($category->children->slice(10) as $child)
                                                        <li class="d-flex w-100 pt-1">
                                                            <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="{{ route('catalog.category', $child->slug) }}">
                                                                {{ $child->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <!-- Promo block - only if active -->
                                        @php $promo = $category->promo_data; @endphp
                                        @if($promo)
                                            <div class="d-none d-lg-block">
                                                <div class="d-none d-xl-block" style="width: 284px"></div>
                                                <div class="d-xl-none" style="width: 240px"></div>
                                                <div class="position-relative d-flex flex-column justify-content-center h-100 bg-body-secondary rounded-5 py-4 px-3">
                                                    <div class="text-center px-2">

                                                        {{-- Badge if exists --}}
                                                        @if($promo['badge_text'])
                                                            <span class="badge bg-{{ $promo['badge_color'] }} bg-opacity-10 text-{{ $promo['badge_color'] }} fs-sm rounded-pill mb-2">
                                                                {{ $promo['badge_text'] }}
                                                            </span>
                                                        @endif

                                                        {{-- Old/New price if exists --}}
                                                        @if($promo['old_price'] && $promo['new_price'])
                                                            <div class="fs-sm text-light-emphasis mb-2">
                                                                Starts from <del>${{ $promo['old_price'] }}</del> ${{ $promo['new_price'] }}
                                                            </div>
                                                        @elseif($promo['old_price'])
                                                            <div class="fs-sm text-light-emphasis mb-2">
                                                                Was <del>${{ $promo['old_price'] }}</del>
                                                            </div>
                                                        @elseif($promo['new_price'])
                                                            <div class="fs-sm text-light-emphasis mb-2">
                                                                Now ${{ $promo['new_price'] }}
                                                            </div>
                                                        @endif

                                                        {{-- Image --}}
                                                        @if($promo['image'])
                                                            <img src="{{ $promo['image'] }}"
                                                                 alt="{{ $promo['title'] }}"
                                                                 class="mb-3"
                                                                 style="max-width: 150px; max-height: 100px; object-fit: contain;">
                                                        @endif

                                                        {{-- Title and subtitle --}}
                                                        <div class="h5 mb-2">{{ $promo['title'] }}</div>
                                                        @if($promo['subtitle'])
                                                            <div class="fs-sm text-light-emphasis mb-3">{{ $promo['subtitle'] }}</div>
                                                        @endif
                                                    </div>

                                                    {{-- Button --}}
                                                    <div class="text-center mt-2">
                                                        <a class="btn btn-sm btn-primary stretched-link" href="{{ $promo['link'] }}">
                                                            {{ $promo['button_text'] }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @else
                            <!-- Category without children -->
                            <li class="position-relative rounded pb-1 px-lg-2">
                                <a class="dropdown-item fw-medium d-flex align-items-center" href="{{ route('catalog.category', $category->slug) }}">
                                    <i class="ci-folder fs-xl opacity-60 pe-1 me-2"></i>
                                    <span class="text-truncate">{{ $category->name }}</span>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
