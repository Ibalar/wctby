<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light" data-pwa="false">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', config('app.name', 'WCT.BY'))</title>
    <meta name="description" content="@yield('meta_description', 'Интернет-магазин электроники и товаров')">
    <meta name="keywords" content="@yield('meta_keywords', 'онлайн магазин, электроника, товары')">
    <meta name="author" content="WebArt.by">

    <!-- Webmanifest + Favicon -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/app-icons/icon-32x32.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('assets/app-icons/icon-180x180.png') }}">

    <!-- Theme switcher (color modes) -->
    <script src="{{ asset('assets/js/theme-switcher.js') }}"></script>

    <!-- Preloaded local web font (Inter) -->
    <link rel="preload" href="{{ asset('assets/fonts/InterVariable.woff2') }}" as="font" type="font/woff2" crossorigin>

    <!-- Font icons -->
    <link rel="preload" href="{{ asset('assets/icons/cartzilla-icons.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('assets/icons/cartzilla-icons.min.css') }}">

    <!-- Vendor styles -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}">

    <!-- Bootstrap + Theme styles -->
    <link rel="preload" href="{{ asset('assets/css/theme.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}" id="theme-styles">



    @stack('styles')
</head>
<body>



<!-- Main content -->
<main class="content-wrapper w-100 px-3 ps-lg-5 pe-lg-4 mx-auto" style="max-width: 1920px">
    <div class="d-lg-flex">

        <!-- Login form + Footer -->
        <div class="d-flex flex-column min-vh-100 w-100 py-4 mx-auto me-lg-5" style="max-width: 416px">

            <!-- Logo -->
            <header class="navbar px-0 pb-4 mt-n2 mt-sm-0 mb-2 mb-md-3 mb-lg-4">
                <a href="{{ url('/') }}" class="navbar-brand pt-0">
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
            </header>

            <h1 class="h2 mt-auto">Создать аккаунт</h1>

            <div class="nav fs-sm mb-4">
                Уже есть аккаунт?
                <a class="nav-link text-decoration-underline p-0 ms-2" href="{{ route('login') }}">
                    Войти
                </a>
            </div>

            {{-- Ошибки --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}" class="needs-validation">
                @csrf

                <div class="position-relative mb-4">
                    <label class="form-label">Email</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control form-control-lg"
                        value="{{ old('email') }}"
                        required
                    >
                </div>

                <div class="position-relative mb-4">
                    <label class="form-label">Логин</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control form-control-lg"
                        value="{{ old('name') }}"
                        required
                    >
                </div>

                <div class="position-relative mb-4">
                    <label class="form-label">Имя</label>
                    <input
                        type="text"
                        name="first_name"
                        class="form-control form-control-lg"
                        value="{{ old('first_name') }}"
                    >
                </div>

                <div class="position-relative mb-4">
                    <label class="form-label">Фамилия</label>
                    <input
                        type="text"
                        name="last_name"
                        class="form-control form-control-lg"
                        value="{{ old('last_name') }}"
                    >
                </div>

                <div class="position-relative mb-4">
                    <label class="form-label">Телефон</label>
                    <input
                        type="text"
                        name="phone"
                        class="form-control form-control-lg"
                        value="{{ old('phone') }}"
                    >
                </div>

                <div class="mb-4">
                    <label class="form-label">Пароль</label>

                    <div class="password-toggle">
                        <input
                            type="password"
                            name="password"
                            class="form-control form-control-lg"
                            minlength="8"
                            required
                        >

                        <label class="password-toggle-button fs-lg">
                            <input type="checkbox" class="btn-check">
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Подтверждение пароля</label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control form-control-lg"
                        required
                    >
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" required id="privacy">
                    <label class="form-check-label" for="privacy">
                        Я принимаю <a href="#">политику конфиденциальности</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-lg btn-primary w-100">
                    Зарегистрироваться
                    <i class="ci-chevron-right fs-lg ms-1 me-n1"></i>
                </button>

            </form>

            <!-- Divider -->
            <div class="d-flex align-items-center my-4">
                <hr class="w-100 m-0">
                <span class="text-body-emphasis fw-medium text-nowrap mx-4">или</span>
                <hr class="w-100 m-0">
            </div>

            <!-- Social login -->
            <div class="d-flex flex-column flex-sm-row gap-3 pb-4">

                <a href="{{ url('/auth/google/redirect') }}" class="btn btn-lg btn-outline-secondary w-100">
                    <i class="ci-google me-1"></i>
                    Google
                </a>

                <a href="{{ url('/auth/facebook/redirect') }}" class="btn btn-lg btn-outline-secondary w-100">
                    <i class="ci-facebook me-1"></i>
                    Facebook
                </a>

                <a href="{{ url('/auth/apple/redirect') }}" class="btn btn-lg btn-outline-secondary w-100">
                    <i class="ci-apple me-1"></i>
                    Apple
                </a>

            </div>

            <!-- Footer -->
            <footer class="mt-auto">
                <p class="fs-xs mb-0">
                    &copy; WCT.BY Все права защищены. Разработка сайта <span class="animate-underline"><a class="animate-target text-dark-emphasis text-decoration-none" href="https://webart.by/" target="_blank" rel="noreferrer">WebArt BY</a></span>
                </p>
            </footer>
        </div>


        <!-- Benefits section that turns into offcanvas on screens < 992px wide (lg breakpoint) -->
        <div class="offcanvas-lg offcanvas-end w-100 py-lg-4 ms-auto" id="benefits" style="max-width: 1034px">
            <div class="offcanvas-header justify-content-end position-relative z-2 p-3">
                <button type="button" class="btn btn-icon btn-outline-dark text-dark border-dark bg-transparent rounded-circle d-none-dark" data-bs-dismiss="offcanvas" data-bs-target="#benefits" aria-label="Close">
                    <i class="ci-close fs-lg"></i>
                </button>
                <button type="button" class="btn btn-icon btn-outline-dark text-light border-light bg-transparent rounded-circle d-none d-inline-flex-dark" data-bs-dismiss="offcanvas" data-bs-target="#benefits" aria-label="Close">
                    <i class="ci-close fs-lg"></i>
                </button>
            </div>
            <div class="position-absolute top-0 start-0 w-100 h-100 d-lg-none">
                <span class="position-absolute top-0 start-0 w-100 h-100 d-none-dark" style="background: linear-gradient(-90deg, #accbee 0%, #e7f0fd 100%)"></span>
                <span class="position-absolute top-0 start-0 w-100 h-100 d-none d-block-dark" style="background: linear-gradient(-90deg, #1b273a 0%, #1f2632 100%)"></span>
            </div>
            <div class="offcanvas-body position-relative z-2 d-lg-flex flex-column align-items-center justify-content-center h-100 pt-2 px-3 p-lg-0">
                <div class="position-absolute top-0 start-0 w-100 h-100 d-none d-lg-block">
                    <span class="position-absolute top-0 start-0 w-100 h-100 rounded-5 d-none-dark" style="background: linear-gradient(-90deg, #accbee 0%, #e7f0fd 100%)"></span>
                    <span class="position-absolute top-0 start-0 w-100 h-100 rounded-5 d-none d-block-dark" style="background: linear-gradient(-90deg, #1b273a 0%, #1f2632 100%)"></span>
                </div>
                <div class="position-relative z-2 w-100 text-center px-md-2 p-lg-5">
                    <h2 class="h4 pb-3">Преимущества учетной записи</h2>
                    <div class="mx-auto" style="max-width: 790px">
                        <div class="row row-cols-1 row-cols-sm-2 g-3 g-md-4 g-lg-3 g-xl-4">
                            <div class="col">
                                <div class="card h-100 bg-transparent border-0">
                                    <span class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-25 border border-white border-opacity-50 rounded-4 d-none-dark"></span>
                                    <span class="position-absolute top-0 start-0 w-100 h-100 bg-white border rounded-4 d-none d-block-dark" style="--cz-bg-opacity: .05"></span>
                                    <div class="card-body position-relative z-2">
                                        <div class="d-inline-flex position-relative text-info p-3">
                                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-white rounded-pill d-none-dark"></span>
                                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-body-secondary rounded-pill d-none d-block-dark"></span>
                                            <i class="ci-mail position-relative z-2 fs-4 m-1"></i>
                                        </div>
                                        <h3 class="h6 pt-2 my-2">Подпишитесь на ваши любимые продукты</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card h-100 bg-transparent border-0">
                                    <span class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-25 border border-white border-opacity-50 rounded-4 d-none-dark"></span>
                                    <span class="position-absolute top-0 start-0 w-100 h-100 bg-white border rounded-4 d-none d-block-dark" style="--cz-bg-opacity: .05"></span>
                                    <div class="card-body position-relative z-2">
                                        <div class="d-inline-flex position-relative text-info p-3">
                                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-white rounded-pill d-none-dark"></span>
                                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-body-secondary rounded-pill d-none d-block-dark"></span>
                                            <i class="ci-settings position-relative z-2 fs-4 m-1"></i>
                                        </div>
                                        <h3 class="h6 pt-2 my-2">Просмотр и управление заказами</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card h-100 bg-transparent border-0">
                                    <span class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-25 border border-white border-opacity-50 rounded-4 d-none-dark"></span>
                                    <span class="position-absolute top-0 start-0 w-100 h-100 bg-white border rounded-4 d-none d-block-dark" style="--cz-bg-opacity: .05"></span>
                                    <div class="card-body position-relative z-2">
                                        <div class="d-inline-flex position-relative text-info p-3">
                                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-white rounded-pill d-none-dark"></span>
                                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-body-secondary rounded-pill d-none d-block-dark"></span>
                                            <i class="ci-gift position-relative z-2 fs-4 m-1"></i>
                                        </div>
                                        <h3 class="h6 pt-2 my-2">Получайте преимущества для будущих покупок</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card h-100 bg-transparent border-0">
                                    <span class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-25 border border-white border-opacity-50 rounded-4 d-none-dark"></span>
                                    <span class="position-absolute top-0 start-0 w-100 h-100 bg-white border rounded-4 d-none d-block-dark" style="--cz-bg-opacity: .05"></span>
                                    <div class="card-body position-relative z-2">
                                        <div class="d-inline-flex position-relative text-info p-3">
                                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-white rounded-pill d-none-dark"></span>
                                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-body-secondary rounded-pill d-none d-block-dark"></span>
                                            <i class="ci-percent position-relative z-2 fs-4 m-1"></i>
                                        </div>
                                        <h3 class="h6 pt-2 my-2">Получайте эксклюзивные предложения и скидки</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card h-100 bg-transparent border-0">
                                    <span class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-25 border border-white border-opacity-50 rounded-4 d-none-dark"></span>
                                    <span class="position-absolute top-0 start-0 w-100 h-100 bg-white border rounded-4 d-none d-block-dark" style="--cz-bg-opacity: .05"></span>
                                    <div class="card-body position-relative z-2">
                                        <div class="d-inline-flex position-relative text-info p-3">
                                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-white rounded-pill d-none-dark"></span>
                                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-body-secondary rounded-pill d-none d-block-dark"></span>
                                            <i class="ci-heart position-relative z-2 fs-4 m-1"></i>
                                        </div>
                                        <h3 class="h6 pt-2 my-2">Создайте список избранных товаров</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card h-100 bg-transparent border-0">
                                    <span class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-25 border border-white border-opacity-50 rounded-4 d-none-dark"></span>
                                    <span class="position-absolute top-0 start-0 w-100 h-100 bg-white border rounded-4 d-none d-block-dark" style="--cz-bg-opacity: .05"></span>
                                    <div class="card-body position-relative z-2">
                                        <div class="d-inline-flex position-relative text-info p-3">
                                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-white rounded-pill d-none-dark"></span>
                                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-body-secondary rounded-pill d-none d-block-dark"></span>
                                            <i class="ci-pie-chart position-relative z-2 fs-4 m-1"></i>
                                        </div>
                                        <h3 class="h6 pt-2 my-2">Оплачивайте покупки удобными способами</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>




<!-- Vendor scripts -->
<script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

<!-- Bootstrap + Theme scripts -->
<script src="{{ asset('assets/js/theme.min.js') }}"></script>

@stack('scripts')
</body>
</html>
