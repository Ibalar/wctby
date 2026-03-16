@extends('layouts.main')



@section('content')

    <!-- Breadcrumb -->
    <nav class="container pt-3 my-3 my-md-4" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home-electronics.html">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Catalog with sidebar filters</li>
        </ol>
    </nav>


    <!-- Page title -->
    <h1 class="h3 container mb-4">Shop catalog</h1>

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
                <div class="row row-cols-2 row-cols-md-3 g-4 pb-3 mb-3">

                    <!-- Item -->
                    <div class="col">
                        <div class="product-card animate-underline hover-effect-opacity bg-body rounded">
                            <div class="position-relative">
                                <div class="position-absolute top-0 end-0 z-2 hover-effect-target opacity-0 mt-3 me-3">
                                    <div class="d-flex flex-column gap-2">
                                        <button type="button" class="btn btn-icon btn-secondary animate-pulse d-none d-lg-inline-flex" aria-label="Add to Wishlist">
                                            <i class="ci-heart fs-base animate-target"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-secondary animate-rotate d-none d-lg-inline-flex" aria-label="Compare">
                                            <i class="ci-refresh-cw fs-base animate-target"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="dropdown d-lg-none position-absolute top-0 end-0 z-2 mt-2 me-2">
                                    <button type="button" class="btn btn-icon btn-sm btn-secondary bg-body" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More actions">
                                        <i class="ci-more-vertical fs-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end fs-xs p-2" style="min-width: auto">
                                        <li>
                                            <a class="dropdown-item" href="#!">
                                                <i class="ci-heart fs-sm ms-n1 me-2"></i>
                                                Add to Wishlist
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#!">
                                                <i class="ci-refresh-cw fs-sm ms-n1 me-2"></i>
                                                Compare
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <a class="d-block rounded-top overflow-hidden p-3 p-sm-4" href="shop-product-general-electronics.html">
                                    <span class="badge bg-danger position-absolute top-0 start-0 mt-2 ms-2 mt-lg-3 ms-lg-3">-21%</span>
                                    <div class="ratio" style="--cz-aspect-ratio: calc(240 / 258 * 100%)">
                                        <img src="assets/img/shop/electronics/01.png" alt="VR Glasses">
                                    </div>
                                </a>
                            </div>
                            <div class="w-100 min-w-0 px-1 pb-2 px-sm-3 pb-sm-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="d-flex gap-1 fs-xs">
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star text-body-tertiary opacity-75"></i>
                                    </div>
                                    <span class="text-body-tertiary fs-xs">(123)</span>
                                </div>
                                <h3 class="pb-1 mb-2">
                                    <a class="d-block fs-sm fw-medium text-truncate" href="shop-product-general-electronics.html">
                                        <span class="animate-target">VRB01 Virtual Reality Glasses</span>
                                    </a>
                                </h3>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="h5 lh-1 mb-0">$340.99 <del class="text-body-tertiary fs-sm fw-normal">$430.00</del></div>
                                    <button type="button" class="product-card-button btn btn-icon btn-secondary animate-slide-end ms-2" aria-label="Add to Cart">
                                        <i class="ci-shopping-cart fs-base animate-target"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="product-card-details position-absolute top-100 start-0 w-100 bg-body rounded-bottom shadow mt-n2 p-3 pt-1">
                                <span class="position-absolute top-0 start-0 w-100 bg-body mt-n2 py-2"></span>
                                <ul class="list-unstyled d-flex flex-column gap-2 m-0">
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Display:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">OLED 1440x1600</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Graphics:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">Adreno 540</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Sound:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">2x3.5mm jack</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Input:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">4 built-in cameras</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Item -->
                    <div class="col">
                        <div class="product-card animate-underline hover-effect-opacity bg-body rounded">
                            <div class="position-relative">
                                <div class="position-absolute top-0 end-0 z-2 hover-effect-target opacity-0 mt-3 me-3">
                                    <div class="d-flex flex-column gap-2">
                                        <button type="button" class="btn btn-icon btn-secondary animate-pulse d-none d-lg-inline-flex" aria-label="Add to Wishlist">
                                            <i class="ci-heart fs-base animate-target"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-secondary animate-rotate d-none d-lg-inline-flex" aria-label="Compare">
                                            <i class="ci-refresh-cw fs-base animate-target"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="dropdown d-lg-none position-absolute top-0 end-0 z-2 mt-2 me-2">
                                    <button type="button" class="btn btn-icon btn-sm btn-secondary bg-body" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More actions">
                                        <i class="ci-more-vertical fs-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end fs-xs p-2" style="min-width: auto">
                                        <li>
                                            <a class="dropdown-item" href="#!">
                                                <i class="ci-heart fs-sm ms-n1 me-2"></i>
                                                Add to Wishlist
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#!">
                                                <i class="ci-refresh-cw fs-sm ms-n1 me-2"></i>
                                                Compare
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <a class="d-block rounded-top overflow-hidden p-3 p-sm-4" href="shop-product-general-electronics.html">
                                    <div class="ratio" style="--cz-aspect-ratio: calc(240 / 258 * 100%)">
                                        <img src="assets/img/shop/electronics/02.png" alt="iPhone 14">
                                    </div>
                                </a>
                            </div>
                            <div class="w-100 min-w-0 px-1 pb-2 px-sm-3 pb-sm-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="d-flex gap-1 fs-xs">
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-half text-warning"></i>
                                    </div>
                                    <span class="text-body-tertiary fs-xs">(142)</span>
                                </div>
                                <h3 class="pb-1 mb-2">
                                    <a class="d-block fs-sm fw-medium text-truncate" href="shop-product-general-electronics.html">
                                        <span class="animate-target">Apple iPhone 14 128GB White</span>
                                    </a>
                                </h3>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="h5 lh-1 mb-0">$899.00</div>
                                    <button type="button" class="product-card-button btn btn-icon btn-secondary animate-slide-end ms-2" aria-label="Add to Cart">
                                        <i class="ci-shopping-cart fs-base animate-target"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="product-card-details position-absolute top-100 start-0 w-100 bg-body rounded-bottom shadow mt-n2 p-3 pt-1">
                                <span class="position-absolute top-0 start-0 w-100 bg-body mt-n2 py-2"></span>
                                <ul class="list-unstyled d-flex flex-column gap-2 m-0">
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Display:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">6.1" XDR</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Capacity:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">128 GB</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Chip:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">A15 Bionic</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Camera:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">12 + 12 MP</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Weight:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">172 grams</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Item -->
                    <div class="col">
                        <div class="product-card animate-underline hover-effect-opacity bg-body rounded">
                            <div class="position-relative">
                                <div class="position-absolute top-0 end-0 z-2 hover-effect-target opacity-0 mt-3 me-3">
                                    <div class="d-flex flex-column gap-2">
                                        <button type="button" class="btn btn-icon btn-secondary animate-pulse d-none d-lg-inline-flex" aria-label="Add to Wishlist">
                                            <i class="ci-heart fs-base animate-target"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-secondary animate-rotate d-none d-lg-inline-flex" aria-label="Compare">
                                            <i class="ci-refresh-cw fs-base animate-target"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="dropdown d-lg-none position-absolute top-0 end-0 z-2 mt-2 me-2">
                                    <button type="button" class="btn btn-icon btn-sm btn-secondary bg-body" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More actions">
                                        <i class="ci-more-vertical fs-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end fs-xs p-2" style="min-width: auto">
                                        <li>
                                            <a class="dropdown-item" href="#!">
                                                <i class="ci-heart fs-sm ms-n1 me-2"></i>
                                                Add to Wishlist
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#!">
                                                <i class="ci-refresh-cw fs-sm ms-n1 me-2"></i>
                                                Compare
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <a class="d-block rounded-top overflow-hidden p-3 p-sm-4" href="shop-product-general-electronics.html">
                                    <div class="ratio" style="--cz-aspect-ratio: calc(240 / 258 * 100%)">
                                        <img src="assets/img/shop/electronics/03.png" alt="Smart Watch">
                                    </div>
                                </a>
                            </div>
                            <div class="w-100 min-w-0 px-1 pb-2 px-sm-3 pb-sm-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="d-flex gap-1 fs-xs">
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                    </div>
                                    <span class="text-body-tertiary fs-xs">(67)</span>
                                </div>
                                <h3 class="pb-1 mb-2">
                                    <a class="d-block fs-sm fw-medium text-truncate" href="shop-product-general-electronics.html">
                                        <span class="animate-target">Smart Watch Series 7, White</span>
                                    </a>
                                </h3>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="h5 lh-1 mb-0">$429.00</div>
                                    <button type="button" class="product-card-button btn btn-icon btn-secondary animate-slide-end ms-2" aria-label="Add to Cart">
                                        <i class="ci-shopping-cart fs-base animate-target"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="product-card-details position-absolute top-100 start-0 w-100 bg-body rounded-bottom shadow mt-n2 p-3 pt-1">
                                <span class="position-absolute top-0 start-0 w-100 bg-body mt-n2 py-2"></span>
                                <ul class="list-unstyled d-flex flex-column gap-2 m-0">
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Display:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">45mm OLED</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Chip:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">64-bit Dual-core</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Connectivity:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">Wi-Fi, Bluetooth</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Power:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">Lithium-ion battery</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Weight:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">37.0 grams</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Item -->
                    <div class="col">
                        <div class="product-card animate-underline hover-effect-opacity bg-body rounded">
                            <div class="posittion-relative">
                                <div class="position-absolute top-0 end-0 z-2 hover-effect-target opacity-0 mt-3 me-3">
                                    <div class="d-flex flex-column gap-2">
                                        <button type="button" class="btn btn-icon btn-secondary animate-pulse d-none d-lg-inline-flex" aria-label="Add to Wishlist">
                                            <i class="ci-heart fs-base animate-target"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-secondary animate-rotate d-none d-lg-inline-flex" aria-label="Compare">
                                            <i class="ci-refresh-cw fs-base animate-target"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="dropdown d-lg-none position-absolute top-0 end-0 z-2 mt-2 me-2">
                                    <button type="button" class="btn btn-icon btn-sm btn-secondary bg-body" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More actions">
                                        <i class="ci-more-vertical fs-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end fs-xs p-2" style="min-width: auto">
                                        <li>
                                            <a class="dropdown-item" href="#!">
                                                <i class="ci-heart fs-sm ms-n1 me-2"></i>
                                                Add to Wishlist
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#!">
                                                <i class="ci-refresh-cw fs-sm ms-n1 me-2"></i>
                                                Compare
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <a class="d-block rounded-top overflow-hidden p-3 p-sm-4" href="shop-product-general-electronics.html">
                                    <div class="ratio" style="--cz-aspect-ratio: calc(240 / 258 * 100%)">
                                        <img src="assets/img/shop/electronics/05.png" alt="iPad Air">
                                    </div>
                                </a>
                            </div>
                            <div class="w-100 min-w-0 px-1 pb-2 px-sm-3 pb-sm-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="d-flex gap-1 fs-xs">
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                    </div>
                                    <span class="text-body-tertiary fs-xs">(12)</span>
                                </div>
                                <h3 class="pb-1 mb-2">
                                    <a class="d-block fs-sm fw-medium text-truncate" href="shop-product-general-electronics.html">
                                        <span class="animate-target">Tablet Apple iPad Air M1</span>
                                    </a>
                                </h3>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="h5 lh-1 mb-0">$540.00</div>
                                    <button type="button" class="product-card-button btn btn-icon btn-secondary animate-slide-end ms-2" aria-label="Add to Cart">
                                        <i class="ci-shopping-cart fs-base animate-target"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="product-card-details position-absolute top-100 start-0 w-100 bg-body rounded-bottom shadow mt-n2 p-3 pt-1">
                                <span class="position-absolute top-0 start-0 w-100 bg-body mt-n2 py-2"></span>
                                <ul class="list-unstyled d-flex flex-column gap-2 m-0">
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Display:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">10.9" LED</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Capacity:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">64 GB</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Chip:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">Apple M1</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Camera:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">12 MP Wide</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Weight:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">462 grams</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Item -->
                    <div class="col">
                        <div class="product-card animate-underline hover-effect-opacity bg-body rounded">
                            <div class="position-relative">
                                <div class="position-absolute top-0 end-0 z-2 hover-effect-target opacity-0 mt-3 me-3">
                                    <div class="d-flex flex-column gap-2">
                                        <button type="button" class="btn btn-icon btn-secondary animate-pulse d-none d-lg-inline-flex" aria-label="Add to Wishlist">
                                            <i class="ci-heart fs-base animate-target"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-secondary animate-rotate d-none d-lg-inline-flex" aria-label="Compare">
                                            <i class="ci-refresh-cw fs-base animate-target"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="dropdown d-lg-none position-absolute top-0 end-0 z-2 mt-2 me-2">
                                    <button type="button" class="btn btn-icon btn-sm btn-secondary bg-body" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More actions">
                                        <i class="ci-more-vertical fs-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end fs-xs p-2" style="min-width: auto">
                                        <li>
                                            <a class="dropdown-item" href="#!">
                                                <i class="ci-heart fs-sm ms-n1 me-2"></i>
                                                Add to Wishlist
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#!">
                                                <i class="ci-refresh-cw fs-sm ms-n1 me-2"></i>
                                                Compare
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <a class="d-block rounded-top overflow-hidden p-3 p-sm-4" href="shop-product-general-electronics.html">
                                    <div class="ratio" style="--cz-aspect-ratio: calc(240 / 258 * 100%)">
                                        <img src="assets/img/shop/electronics/06.png" alt="AirPods 2">
                                    </div>
                                </a>
                            </div>
                            <div class="w-100 min-w-0 px-1 pb-2 px-sm-3 pb-sm-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="d-flex gap-1 fs-xs">
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star text-body-tertiary opacity-75"></i>
                                    </div>
                                    <span class="text-body-tertiary fs-xs">(78)</span>
                                </div>
                                <h3 class="pb-1 mb-2">
                                    <a class="d-block fs-sm fw-medium text-truncate" href="shop-product-general-electronics.html">
                                        <span class="animate-target">Headphones Apple AirPods 2 Pro</span>
                                    </a>
                                </h3>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="h5 lh-1 mb-0">$224.00</div>
                                    <button type="button" class="product-card-button btn btn-icon btn-secondary animate-slide-end ms-2" aria-label="Add to Cart">
                                        <i class="ci-shopping-cart fs-base animate-target"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="product-card-details position-absolute top-100 start-0 w-100 bg-body rounded-bottom shadow mt-n2 p-3 pt-1">
                                <span class="position-absolute top-0 start-0 w-100 bg-body mt-n2 py-2"></span>
                                <ul class="list-unstyled d-flex flex-column gap-2 m-0">
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Audio:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">Noise Cancellation</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Sensors:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">Touch control</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Chip:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">Apple H2</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Weight:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">50.8 grams</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Item -->
                    <div class="col">
                        <div class="product-card animate-underline hover-effect-opacity bg-body rounded">
                            <div class="position-relative">
                                <div class="position-absolute top-0 end-0 z-2 hover-effect-target opacity-0 mt-3 me-3">
                                    <div class="d-flex flex-column gap-2">
                                        <button type="button" class="btn btn-icon btn-secondary animate-pulse d-none d-lg-inline-flex" aria-label="Add to Wishlist">
                                            <i class="ci-heart fs-base animate-target"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-secondary animate-rotate d-none d-lg-inline-flex" aria-label="Compare">
                                            <i class="ci-refresh-cw fs-base animate-target"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="dropdown d-lg-none position-absolute top-0 end-0 z-2 mt-2 me-2">
                                    <button type="button" class="btn btn-icon btn-sm btn-secondary bg-body" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More actions">
                                        <i class="ci-more-vertical fs-lg"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end fs-xs p-2" style="min-width: auto">
                                        <li>
                                            <a class="dropdown-item" href="#!">
                                                <i class="ci-heart fs-sm ms-n1 me-2"></i>
                                                Add to Wishlist
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#!">
                                                <i class="ci-refresh-cw fs-sm ms-n1 me-2"></i>
                                                Compare
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <a class="d-block rounded-top overflow-hidden p-3 p-sm-4" href="shop-product-general-electronics.html">
                                    <span class="badge bg-info position-absolute top-0 start-0 mt-2 ms-2 mt-lg-3 ms-lg-3">New</span>
                                    <div class="ratio" style="--cz-aspect-ratio: calc(240 / 258 * 100%)">
                                        <img src="assets/img/shop/electronics/04.png" alt="MacBook">
                                    </div>
                                </a>
                            </div>
                            <div class="w-100 min-w-0 px-1 pb-2 px-sm-3 pb-sm-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="d-flex gap-1 fs-xs">
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-filled text-warning"></i>
                                        <i class="ci-star-half text-warning"></i>
                                    </div>
                                    <span class="text-body-tertiary fs-xs">(51)</span>
                                </div>
                                <h3 class="pb-1 mb-2">
                                    <a class="d-block fs-sm fw-medium text-truncate" href="shop-product-general-electronics.html">
                                        <span class="animate-target">Laptop Apple MacBook Pro 13 M2</span>
                                    </a>
                                </h3>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="h5 lh-1 mb-0">$1,200.00</div>
                                    <button type="button" class="product-card-button btn btn-icon btn-secondary animate-slide-end ms-2" aria-label="Add to Cart">
                                        <i class="ci-shopping-cart fs-base animate-target"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="product-card-details position-absolute top-100 start-0 w-100 bg-body rounded-bottom shadow mt-n2 p-3 pt-1">
                                <span class="position-absolute top-0 start-0 w-100 bg-body mt-n2 py-2"></span>
                                <ul class="list-unstyled d-flex flex-column gap-2 m-0">
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Chip:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">Apple M2</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Memory:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">8 GB unified</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Storage:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">256 GB SSD</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="fs-xs">Display:</span>
                                        <span class="d-block flex-grow-1 border-bottom border-dashed px-1 mt-2 mx-2"></span>
                                        <span class="text-dark-emphasis fs-xs fw-medium text-end">13.3-inch Retina</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>





                <!-- Pagination -->
                <nav class="border-top mt-4 pt-3" aria-label="Catalog pagination">
                    <ul class="pagination pagination-lg pt-2 pt-md-3">
                        <li class="page-item disabled me-auto">
                            <a class="page-link d-flex align-items-center h-100 fs-lg px-2" href="#!" aria-label="Previous page">
                                <i class="ci-chevron-left mx-1"></i>
                            </a>
                        </li>
                        <li class="page-item active" aria-current="page">
                  <span class="page-link">
                    1
                    <span class="visually-hidden">(current)</span>
                  </span>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#!">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#!">3</a>
                        </li>
                        <li class="page-item">
                            <span class="page-link pe-none">...</span>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#!">16</a>
                        </li>
                        <li class="page-item ms-auto">
                            <a class="page-link d-flex align-items-center h-100 fs-lg px-2" href="#!" aria-label="Next page">
                                <i class="ci-chevron-right mx-1"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>



@endsection
