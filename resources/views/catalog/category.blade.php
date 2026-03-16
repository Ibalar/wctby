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
                        <button type="button" class="btn btn-sm btn-secondary bg-transparent border-0 text-decoration-underline px-0 ms-2">
                            Clear all
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end text-nowrap">
                    <label class="form-label fw-semibold mb-0 me-2">Sort by:</label>
                    <div style="width: 190px">
                        <select class="form-select border-0 rounded-0 px-1" data-select='{
                  "removeItemButton": false,
                  "classNames": {
                    "containerInner": ["form-select", "border-0", "rounded-0", "px-1"]
                  }
                }'>
                            <option value="Relevance">Relevance</option>
                            <option value="Popularity">Popularity</option>
                            <option value="Price: Low to High">Price: Low to High</option>
                            <option value="Price: High to Low">Price: High to Low</option>
                            <option value="Newest Arrivals">Newest Arrivals</option>
                        </select>
                    </div>
                </div>
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
                        <h5 class="offcanvas-title">Filter and sort</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#filterSidebar" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body flex-column pt-2 py-lg-0">

                        <!-- Status -->
                        <div class="w-100 border rounded p-3 p-xl-4 mb-3 mb-xl-4">
                            <h4 class="h6">Status</h4>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary">
                                    <i class="ci-percent fs-sm me-1 ms-n1"></i>
                                    Sale
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary">Same Day Delivery</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary">Available to Order</button>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="w-100 border rounded p-3 p-xl-4 mb-3 mb-xl-4">
                            <h4 class="h6 mb-2">Categories</h4>
                            <ul class="list-unstyled d-block m-0">
                                <li class="nav d-block pt-2 mt-1">
                                    <a class="nav-link animate-underline fw-normal p-0" href="#!">
                                        <span class="animate-target text-truncate me-3">Smartphones</span>
                                        <span class="text-body-secondary fs-xs ms-auto">218</span>
                                    </a>
                                </li>
                                <li class="nav d-block pt-2 mt-1">
                                    <a class="nav-link animate-underline fw-normal p-0" href="#!">
                                        <span class="animate-target text-truncate me-3">Accessories</span>
                                        <span class="text-body-secondary fs-xs ms-auto">372</span>
                                    </a>
                                </li>
                                <li class="nav d-block pt-2 mt-1">
                                    <a class="nav-link animate-underline fw-normal p-0" href="#!">
                                        <span class="animate-target text-truncate me-3">Tablets</span>
                                        <span class="text-body-secondary fs-xs ms-auto">110</span>
                                    </a>
                                </li>
                                <li class="nav d-block pt-2 mt-1">
                                    <a class="nav-link animate-underline fw-normal p-0" href="#!">
                                        <span class="animate-target text-truncate me-3">Wearable Electronics</span>
                                        <span class="text-body-secondary fs-xs ms-auto">142</span>
                                    </a>
                                </li>
                                <li class="nav d-block pt-2 mt-1">
                                    <a class="nav-link animate-underline fw-normal p-0" href="#!">
                                        <span class="animate-target text-truncate me-3">Computers &amp; Laptops</span>
                                        <span class="text-body-secondary fs-xs ms-auto">205</span>
                                    </a>
                                </li>
                                <li class="nav d-block pt-2 mt-1">
                                    <a class="nav-link animate-underline fw-normal p-0" href="#!">
                                        <span class="animate-target text-truncate me-3">Cameras, Photo &amp; Video</span>
                                        <span class="text-body-secondary fs-xs ms-auto">78</span>
                                    </a>
                                </li>
                                <li class="nav d-block pt-2 mt-1">
                                    <a class="nav-link animate-underline fw-normal p-0" href="#!">
                                        <span class="animate-target text-truncate me-3">Headphones</span>
                                        <span class="text-body-secondary fs-xs ms-auto">121</span>
                                    </a>
                                </li>
                                <li class="nav d-block pt-2 mt-1">
                                    <a class="nav-link animate-underline fw-normal p-0" href="#!">
                                        <span class="animate-target text-truncate me-3">Video Games</span>
                                        <span class="text-body-secondary fs-xs ms-auto">89</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Price range -->
                        <div class="w-100 border rounded p-3 p-xl-4 mb-3 mb-xl-4">
                            <h4 class="h6 mb-2" id="slider-label">Price</h4>
                            <div class="range-slider" data-range-slider='{"startMin": 340, "startMax": 1250, "min": 0, "max": 1600, "step": 1, "tooltipPrefix": "$"}' aria-labelledby="slider-label">
                                <div class="range-slider-ui"></div>
                                <div class="d-flex align-items-center">
                                    <div class="position-relative w-50">
                                        <i class="ci-dollar-sign position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                                        <input type="number" class="form-control form-icon-start" min="0" data-range-slider-min>
                                    </div>
                                    <i class="ci-minus text-body-emphasis mx-2"></i>
                                    <div class="position-relative w-50">
                                        <i class="ci-dollar-sign position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                                        <input type="number" class="form-control form-icon-start" min="0" data-range-slider-max>
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
            <div class="col-lg-9">
                @if($category->products->isEmpty())
                    <div class="alert alert-info">В этой категории пока нет товаров</div>
                @else
                    <div class="row row-cols-2 row-cols-md-3 g-4 pb-3 mb-3">
                        @foreach($category->products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>


@endsection
