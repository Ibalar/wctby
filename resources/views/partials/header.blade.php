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
                        <svg width="57" height="45" viewBox="0 0 65 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M57.4468 37.1226H52.2317C51.9852 37.1226 51.775 37.2437 51.6524 37.4568L49.3004 41.5314C49.1779 41.7444 49.1779 41.9881 49.3004 42.2012C49.423 42.4142 49.6346 42.5354 49.8797 42.5354C53.3736 42.5354 56.8689 42.5354 60.3628 42.5354H61.7261C63.5267 42.5354 65 41.0621 65 39.2615V34.506V28.4762V14.3405V8.31073V3.27527C65 1.59725 63.7202 0.20192 62.0868 0.0222813C61.8166 -0.00835478 61.5688 0.116974 61.4337 0.352315L58.3854 5.63147L56.3885 9.09056L51.1595 18.1463L48.146 23.367L46.8105 25.68L45.0698 28.6935L41.945 34.1063L40.2043 37.1212L35.2454 45.7104C35.1229 45.9235 34.9112 46.0446 34.6661 46.0446H30.8199C30.5748 46.0446 30.3631 45.9235 30.2406 45.7104C30.118 45.4974 30.118 45.2537 30.2406 45.042L31.3532 43.1147C31.4758 42.9016 31.6874 42.7805 31.9325 42.7805H33.0702C33.3153 42.7805 33.527 42.6593 33.6495 42.4463L36.7229 37.1226L38.4636 34.1077L41.5885 28.6949L43.3292 25.6814L48.0805 17.4514L50.1415 13.8823L53.2887 8.43049L55.0293 5.41562L57.5749 1.00681C57.6975 0.79375 57.6975 0.550054 57.5749 0.336994C57.4524 0.123934 57.2407 0.00278357 56.9956 0.00278357H49.9939H46.5125H39.8534H38.3522H34.8708H32.5675H27.0962C26.8497 0.00278357 26.6395 0.123934 26.5169 0.336994L23.5856 5.41562L21.8449 8.43049L18.72 13.8433L13.8447 22.2877L8.43188 31.6624L6.66613 34.7204C6.51295 34.9864 6.21077 35.1117 5.91276 35.0324C5.61615 34.953 5.41701 34.6926 5.41701 34.3862V30.8547V16.7189V10.6892V7.55318C5.41701 6.37509 6.37787 5.41562 7.55458 5.41562H8.07539C8.32187 5.41562 8.53214 5.29447 8.65469 5.08141L11.0067 1.00681C11.1293 0.79375 11.1293 0.550054 11.0067 0.336994C10.8842 0.123934 10.6725 0.00278357 10.4274 0.00278357H7.87765H3.42289H3.27388C1.47332 0.00278357 0 1.4761 0 3.27667V5.93086V20.0666V26.0964V39.2643C0 39.5386 0.0348125 39.806 0.0988698 40.0608C0.373202 41.1456 1.19202 42.0202 2.24618 42.3711C2.56925 42.4783 2.916 42.5368 3.27388 42.5368H7.87487H9.92609C10.1712 42.5368 10.3828 42.4156 10.5054 42.2026L13.4367 37.124L15.1774 34.1091L18.3023 28.6963L20.043 25.6828L25.1383 16.8582L26.879 13.8447L30.0039 8.43188L31.551 5.75122C31.6735 5.53817 31.8852 5.41701 32.1303 5.41701H35.2245H43.3862H45.7076C45.9541 5.41701 46.1644 5.53956 46.2869 5.75122C46.4095 5.96428 46.4095 6.20798 46.2869 6.42104L45.1255 8.43188L42.0007 13.8447L40.26 16.8582L35.1646 25.6828L33.424 28.6963L30.2991 34.1091L28.5584 37.124L25.4335 42.5368L24.4615 44.2204L21.2545 49.7753C21.0484 50.1332 21.0484 50.5426 21.2545 50.8991C21.4606 51.2569 21.8157 51.4616 22.2279 51.4616H32.4979H42.768C43.1801 51.4616 43.5352 51.2569 43.7413 50.8991C43.9474 50.5426 43.9474 50.1318 43.7413 49.7753L40.5343 44.2204L40.1416 43.5408C40.0191 43.3278 40.0191 43.0841 40.1416 42.871C40.2642 42.6579 40.4758 42.5368 40.7209 42.5368H44.8498C45.0963 42.5368 45.3066 42.4156 45.4291 42.2026L48.3604 37.124L50.1011 34.1091L56.5667 22.9102L58.3325 19.8522C58.4857 19.5862 58.7878 19.4609 59.0858 19.5402C59.3825 19.6196 59.5816 19.88 59.5816 20.1864V23.7179V34.9864C59.5844 36.1617 58.6235 37.1212 57.4468 37.1226ZM38.5193 13.8419L41.0649 9.43312C41.1874 9.22006 41.1874 8.97637 41.0649 8.76331C40.9423 8.55025 40.7307 8.4291 40.4856 8.4291H33.8696C33.6231 8.4291 33.4128 8.55025 33.2903 8.76331L30.359 13.8419L28.6183 16.8554L23.5229 25.68L21.7822 28.6935L18.6574 34.1063L16.9167 37.1212L14.3711 41.53C14.2486 41.743 14.2486 41.9867 14.3711 42.1998C14.4936 42.4129 14.7053 42.534 14.9504 42.534H17.5057H21.5664C21.8129 42.534 22.0232 42.4129 22.1457 42.1998L25.077 37.1212L26.8177 34.1063L29.9426 28.6935L31.6833 25.68L36.7786 16.8554L38.5193 13.8419ZM10.199 8.4291L8.51822 11.3409C8.45695 11.4468 8.4291 11.5526 8.4291 11.6751V23.133C8.4291 23.4408 8.62823 23.6998 8.92484 23.7792C9.22146 23.8585 9.52364 23.7332 9.67821 23.4672C12.7293 18.1839 15.3125 13.7082 18.3621 8.4277L20.1028 5.41284L22.6484 1.00403C22.771 0.790967 22.771 0.547271 22.6484 0.334211C22.5259 0.121151 22.3142 0 22.0691 0H15.4531C15.2066 0 14.9964 0.121151 14.8738 0.334211L11.9425 5.41284L10.199 8.4291Z" fill="url(#paint0_linear_1_48)" />
                          <defs>
                            <linearGradient id="paint0_linear_1_48" x1="13.3531" y1="49.731" x2="51.2272" y2="-7.16519" gradientUnits="userSpaceOnUse">
                              <stop stop-color="#3E12EB" />
                              <stop offset="1" stop-color="#97F8F7" />
                            </linearGradient>
                          </defs>
                        </svg>
                    </span>
                    WCT.BY
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
                    @auth
                        <a class="btn btn-icon btn-lg fs-lg btn-outline-secondary border-0 rounded-circle animate-shake d-none d-md-inline-flex"
                           href="{{ route('profile.index') }}">
                            <i class="ci-user animate-target"></i>
                            <span class="visually-hidden">Аккаунт</span>
                        </a>
                    @endauth

                    @guest
                        <a class="btn btn-icon btn-lg fs-lg btn-outline-secondary border-0 rounded-circle animate-shake d-none d-md-inline-flex"
                           href="{{ route('login') }}">
                            <i class="ci-user animate-target"></i>
                            <span class="visually-hidden">Войти</span>
                        </a>
                    @endguest

                    <!-- Wishlist button visible on screens > 768px wide (md breakpoint) -->
                    <a class="btn btn-icon btn-lg fs-lg btn-outline-secondary border-0 rounded-circle animate-pulse d-none d-md-inline-flex" href="#">
                        <i class="ci-heart animate-target"></i>
                        <span class="visually-hidden">Избранное</span>
                    </a>

                    <!-- Cart button -->
                    <button type="button" class="btn btn-icon btn-lg btn-secondary position-relative rounded-circle ms-2" data-bs-toggle="offcanvas" data-bs-target="#shoppingCart" aria-controls="shoppingCart" aria-label="Shopping cart">
                        <span class="position-absolute top-0 start-100 mt-n1 ms-n3 badge text-bg-success border border-3 border-dark rounded-pill" data-cart-count-badge style="--cz-badge-padding-y: .25em; --cz-badge-padding-x: .42em">{{ $cartCount ?? 0 }}</span>
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
    <div class="collapse bg-dark-two navbar-stuck-hide" id="stuckNav">
        <nav class="offcanvas offcanvas-start" id="navbarNav" tabindex="-1" aria-labelledby="navbarNavLabel">
            <div class="offcanvas-header py-3">
                <h5 class="offcanvas-title" id="navbarNavLabel">Меню</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body py-3 py-lg-0">
                <div class="container px-0 px-lg-3">
                    <div class="row">
                        <!-- Categories mega menu -->
                        @include('partials.categories-mega-menu', ['categories' => $categories])

                        <!-- Navbar nav -->
                        <div class="col-lg-9 d-lg-flex align-self-center pt-3 pt-lg-0 ps-lg-0">
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
