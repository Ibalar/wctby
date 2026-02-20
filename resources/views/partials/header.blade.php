<!-- Navigation bar (Page header) -->
<header class="navbar navbar-expand-lg navbar-dark bg-dark d-block z-fixed p-0" data-sticky-navbar='{"offset": 500}'>
    <div class="container d-block py-1 py-lg-3" data-bs-theme="dark">
        <div class="navbar-stuck-hide pt-1"></div>
        <div class="row flex-nowrap align-items-center g-0">
            <div class="col col-lg-3 d-flex align-items-center">
                <!-- Mobile offcanvas menu toggler (Hamburger) -->
                <button type="button" class="navbar-toggler me-4 me-lg-0" data-bs-toggle="offcanvas" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar brand (Logo) -->
                <a href="{{ url('/') }}" class="navbar-brand me-0">
                    <span class="d-none d-sm-flex flex-shrink-0 text-primary me-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"><path d="M36 18.01c0 8.097-5.355 14.949-12.705 17.2a18.12 18.12 0 0 1-5.315.79C9.622 36 2.608 30.313.573 22.611.257 21.407.059 20.162 0 18.879v-1.758c.02-.395.059-.79.099-1.185.099-.908.277-1.817.514-2.686C2.687 5.628 9.682 0 18 0c5.572 0 10.551 2.528 13.871 6.517 1.502 1.797 2.648 3.91 3.359 6.201.494 1.659.771 3.436.771 5.292z" fill="currentColor"/><g fill="#fff"><path d="M17.466 21.624c-.514 0-.988-.316-1.146-.829-.198-.632.138-1.303.771-1.501l7.666-2.469-1.205-8.254-13.317 4.621a1.19 1.19 0 0 1-1.521-.75 1.19 1.19 0 0 1 .751-1.521l13.89-4.818c.553-.197 1.166-.138 1.64.158a1.82 1.82 0 0 1 .85 1.284l1.344 9.183c.138.987-.494 1.994-1.482 2.33l-7.864 2.528-.375.04zm7.31.138c-.178-.632-.85-1.007-1.482-.81l-5.177 1.58c-2.331.79-3.28.02-3.418-.099l-6.56-8.412a4.25 4.25 0 0 0-4.406-1.758l-3.122.987c-.237.889-.415 1.777-.514 2.686l4.228-1.363a1.84 1.84 0 0 1 1.857.81l6.659 8.551c.751.948 2.015 1.323 3.359 1.323.909 0 1.857-.178 2.687-.474l5.078-1.54c.632-.178 1.008-.829.81-1.481z"/><use href="#czlogo"/><use href="#czlogo" x="8.516" y="-2.172"/></g><defs><path id="czlogo" d="M18.689 28.654a1.94 1.94 0 0 1-1.936 1.935 1.94 1.94 0 0 1-1.936-1.935 1.94 1.94 0 0 1 1.936-1.935 1.94 1.94 0 0 1 1.936 1.935z"/></defs></svg>
                    </span>
                    {{ config('app.name', 'WCT.BY') }}
                </a>
            </div>
            <div class="col col-lg-9 d-flex align-items-center justify-content-end">
                <!-- Search visible on screens > 991px wide (lg breakpoint) -->
                <div class="position-relative flex-fill d-none d-lg-block pe-4 pe-xl-5">
                    <i class="ci-search position-absolute top-50 translate-middle-y d-flex fs-lg text-white ms-3"></i>
                    <input type="search" class="form-control form-control-lg form-icon-start border-white rounded-pill" placeholder="Поиск товаров">
                </div>

                <!-- Sale link visible on screens > 1200px wide (xl breakpoint) -->
                <a class="d-none d-xl-flex align-items-center text-decoration-none animate-shake navbar-stuck-hide me-3 me-xl-4 me-xxl-5" href="#">
                    <div class="btn btn-icon btn-lg fs-lg text-primary bg-body-secondary bg-opacity-75 pe-none rounded-circle">
                        <i class="ci-percent animate-target"></i>
                    </div>
                    <div class="ps-2 text-nowrap">
                        <div class="fs-xs text-body">Только в этом месяце</div>
                        <div class="fw-medium text-white">Супер скидки 20%</div>
                    </div>
                </a>

                <!-- Button group -->
                <div class="d-flex align-items-center">
                    <!-- Navbar stuck nav toggler -->
                    <button type="button" class="navbar-toggler d-none navbar-stuck-show me-3" data-bs-toggle="collapse" data-bs-target="#stuckNav" aria-controls="stuckNav" aria-expanded="false" aria-label="Toggle navigation in navbar stuck state">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Theme switcher (light/dark/auto) -->
                    <div class="dropdown">
                        <button type="button" class="theme-switcher btn btn-icon btn-lg btn-outline-secondary fs-lg border-0 rounded-circle animate-scale" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Toggle theme (light)">
                            <span class="theme-icon-active d-flex animate-target">
                                <i class="ci-sun"></i>
                            </span>
                        </button>
                        <ul class="dropdown-menu" style="--cz-dropdown-min-width: 9rem">
                            <li>
                                <button type="button" class="dropdown-item active" data-bs-theme-value="light" aria-pressed="true">
                                    <span class="theme-icon d-flex fs-base me-2">
                                        <i class="ci-sun"></i>
                                    </span>
                                    <span class="theme-label">Светлая</span>
                                    <i class="item-active-indicator ci-check ms-auto"></i>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item" data-bs-theme-value="dark" aria-pressed="false">
                                    <span class="theme-icon d-flex fs-base me-2">
                                        <i class="ci-moon"></i>
                                    </span>
                                    <span class="theme-label">Тёмная</span>
                                    <i class="item-active-indicator ci-check ms-auto"></i>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item" data-bs-theme-value="auto" aria-pressed="false">
                                    <span class="theme-icon d-flex fs-base me-2">
                                        <i class="ci-auto"></i>
                                    </span>
                                    <span class="theme-label">Авто</span>
                                    <i class="item-active-indicator ci-check ms-auto"></i>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- Search toggle button visible on screens < 992px wide (lg breakpoint) -->
                    <button type="button" class="btn btn-icon btn-lg fs-xl btn-outline-secondary border-0 rounded-circle animate-shake d-lg-none" data-bs-toggle="collapse" data-bs-target="#searchBar" aria-expanded="false" aria-controls="searchBar" aria-label="Toggle search bar">
                        <i class="ci-search animate-target"></i>
                    </button>

                    <!-- Account button visible on screens > 768px wide (md breakpoint) -->
                    <a class="btn btn-icon btn-lg fs-lg btn-outline-secondary border-0 rounded-circle animate-shake d-none d-md-inline-flex" href="#">
                        <i class="ci-user animate-target"></i>
                        <span class="visually-hidden">Аккаунт</span>
                    </a>

                    <!-- Wishlist button visible on screens > 768px wide (md breakpoint) -->
                    <a class="btn btn-icon btn-lg fs-lg btn-outline-secondary border-0 rounded-circle animate-pulse d-none d-md-inline-flex" href="#">
                        <i class="ci-heart animate-target"></i>
                        <span class="visually-hidden">Избранное</span>
                    </a>

                    <!-- Cart button -->
                    <button type="button" class="btn btn-icon btn-lg btn-secondary position-relative rounded-circle ms-2" data-bs-toggle="offcanvas" data-bs-target="#shoppingCart" aria-controls="shoppingCart" aria-label="Shopping cart">
                        <span class="position-absolute top-0 start-100 mt-n1 ms-n3 badge text-bg-success border border-3 border-dark rounded-pill" style="--cz-badge-padding-y: .25em; --cz-badge-padding-x: .42em">3</span>
                        <span class="position-absolute top-0 start-0 d-flex align-items-center justify-content-center w-100 h-100 rounded-circle animate-slide-end fs-lg">
                            <i class="ci-shopping-cart animate-target ms-n1"></i>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="navbar-stuck-hide pb-1"></div>
    </div>

    <!-- Search visible on screens < 992px wide (lg breakpoint). It is hidden inside collapse by default -->
    <div class="collapse position-absolute top-100 z-2 w-100 bg-dark d-lg-none" id="searchBar">
        <div class="container position-relative my-3" data-bs-theme="dark">
            <i class="ci-search position-absolute top-50 translate-middle-y d-flex fs-lg text-white ms-3"></i>
            <input type="search" class="form-control form-icon-start border-white rounded-pill" placeholder="Поиск товаров" data-autofocus="collapse">
        </div>
    </div>

    <!-- Main navigation that turns into offcanvas on screens < 992px wide (lg breakpoint) -->
    <div class="collapse navbar-stuck-hide" id="stuckNav">
        <nav class="offcanvas offcanvas-start" id="navbarNav" tabindex="-1" aria-labelledby="navbarNavLabel">
            <div class="offcanvas-header py-3">
                <h5 class="offcanvas-title" id="navbarNavLabel">Меню</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body py-3 py-lg-0">
                <div class="container px-0 px-lg-3">
                    <div class="row">
                        <!-- Categories mega menu -->
                        <div class="col-lg-3">
                            <div class="navbar-nav">
                                <div class="dropdown w-100">
                                    <!-- Button visible on screens > 991px wide (lg breakpoint) -->
                                    <div class="cursor-pointer d-none d-lg-block" data-bs-toggle="dropdown" data-bs-trigger="hover" data-bs-theme="dark">
                                        <a class="position-absolute top-0 start-0 w-100 h-100" href="#">
                                            <span class="visually-hidden">Категории</span>
                                        </a>
                                        <button type="button" class="btn btn-lg btn-secondary dropdown-toggle w-100 rounded-bottom-0 justify-content-start pe-none">
                                            <i class="ci-grid fs-lg"></i>
                                            <span class="ms-2 me-auto">Каталог</span>
                                        </button>
                                    </div>

                                    <!-- Button visible on screens < 992px wide (lg breakpoint) -->
                                    <button type="button" class="btn btn-lg btn-secondary dropdown-toggle w-100 justify-content-start d-lg-none mb-2" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                        <i class="ci-grid fs-lg"></i>
                                        <span class="ms-2 me-auto">Каталог</span>
                                    </button>

                                    <!-- Simple dropdown menu -->
                                    <ul class="dropdown-menu dropdown-menu-static w-100 rounded-top-0 rounded-bottom-4 py-1 p-lg-1" style="--cz-dropdown-spacer: 0; --cz-dropdown-item-padding-y: .625rem; --cz-dropdown-item-spacer: 0">
                                        <li class="d-lg-none pt-2">
                                            <a class="dropdown-item fw-medium" href="#">
                                                <i class="ci-grid fs-xl opacity-60 pe-1 me-2"></i>
                                                Все категории
                                                <i class="ci-chevron-right fs-base ms-auto me-n1"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item fw-medium" href="#">
                                                <i class="ci-computer fs-xl opacity-60 pe-1 me-2"></i>
                                                <span class="text-truncate">Компьютеры и аксессуары</span>
                                                <i class="ci-chevron-right fs-base ms-auto me-n1"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item fw-medium" href="#">
                                                <i class="ci-smartphone-2 fs-xl opacity-60 pe-1 me-2"></i>
                                                <span class="text-truncate">Смартфоны и планшеты</span>
                                                <i class="ci-chevron-right fs-base ms-auto me-n1"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item fw-medium" href="#">
                                                <i class="ci-monitor-2 fs-xl opacity-60 pe-1 me-2"></i>
                                                <span class="text-truncate">ТВ, видео и аудио</span>
                                                <i class="ci-chevron-right fs-base ms-auto me-n1"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item fw-medium" href="#">
                                                <i class="ci-camera-2 fs-xl opacity-60 pe-1 me-2"></i>
                                                <span class="text-truncate">Камеры и фото</span>
                                                <i class="ci-chevron-right fs-base ms-auto me-n1"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item fw-medium" href="#">
                                                <i class="ci-headphones-2 fs-xl opacity-60 pe-1 me-2"></i>
                                                <span class="text-truncate">Наушники</span>
                                                <i class="ci-chevron-right fs-base ms-auto me-n1"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item fw-medium" href="#">
                                                <i class="ci-watch-2 fs-xl opacity-60 pe-1 me-2"></i>
                                                <span class="text-truncate">Умные часы</span>
                                                <i class="ci-chevron-right fs-base ms-auto me-n1"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Navbar nav -->
                        <div class="col-lg-9 d-lg-flex pt-3 pt-lg-0 ps-lg-0">
                            <ul class="navbar-nav position-relative">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="{{ url('/') }}">Главная</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Каталог</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Акции</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" data-bs-trigger="hover" aria-expanded="false">Страницы</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">О нас</a></li>
                                        <li><a class="dropdown-item" href="#">Контакты</a></li>
                                        <li><a class="dropdown-item" href="#">Доставка</a></li>
                                        <li><a class="dropdown-item" href="#">Оплата</a></li>
                                    </ul>
                                </li>
                            </ul>
                            <hr class="d-lg-none my-3">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item dropdown me-lg-n2 me-xl-n1">
                                    <a class="nav-link dropdown-toggle fs-sm px-3" href="#" role="button" data-bs-toggle="dropdown" data-bs-trigger="hover" aria-expanded="false">Рус</a>
                                    <ul class="dropdown-menu fs-sm" style="--cz-dropdown-min-width: 7.5rem; --cz-dropdown-spacer: .25rem">
                                        <li><a class="dropdown-item" href="#">English</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown me-lg-n1">
                                    <a class="nav-link dropdown-toggle fs-sm px-3" href="#" role="button" data-bs-toggle="dropdown" data-bs-trigger="hover" aria-expanded="false">BYN (р)</a>
                                    <ul class="dropdown-menu dropdown-menu-end fs-sm" style="--cz-dropdown-min-width: 7rem; --cz-dropdown-spacer: .25rem">
                                        <li><a class="dropdown-item" href="#">USD ($)</a></li>
                                        <li><a class="dropdown-item" href="#">EUR (€)</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="offcanvas-header border-top px-0 py-3 mt-3 d-md-none">
                <div class="nav nav-justified w-100">
                    <a class="nav-link border-end" href="#">
                        <i class="ci-user fs-lg opacity-60 me-2"></i>
                        Аккаунт
                    </a>
                    <a class="nav-link" href="#">
                        <i class="ci-heart fs-lg opacity-60 me-2"></i>
                        Избранное
                    </a>
                </div>
            </div>
        </nav>
    </div>
</header>
