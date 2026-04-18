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
    <link rel="stylesheet" href="{{ asset('assets/vendor/simplebar/simplebar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/choices/choices.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/nouislider/nouislider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/glightbox/glightbox.min.css') }}">

    <!-- Bootstrap + Theme styles -->
    <link rel="preload" href="{{ asset('assets/css/theme.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}" id="theme-styles">



    @stack('styles')
</head>
<body>
    <!-- Shopping cart offcanvas -->
    @include('partials.cart-offcanvas')

    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1085">
        @if(session('success'))
            <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-flash-toast data-bs-delay="3500">
                <div class="d-flex">
                    <div class="toast-body">{{ session('success') }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-flash-toast data-bs-delay="4500">
                <div class="d-flex">
                    <div class="toast-body">{{ session('error') }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    @include('partials.top-bar')
    <!-- Header -->
    @include('partials.header')

    @auth
        @php($authUser = auth()->user())
        @if(
            $authUser
            && method_exists($authUser, 'hasVerifiedEmail')
            && ! $authUser->hasVerifiedEmail()
            && ! request()->routeIs('verification.notice')
        )
            <section class="container pt-3">
                <div class="alert alert-warning d-md-flex align-items-center justify-content-between gap-3 mb-0" role="alert">
                    <div>
                        <div class="fw-semibold mb-1">Подтвердите email, чтобы открыть все возможности профиля</div>
                        <div class="small mb-0">Мы отправим ссылку повторно на адрес {{ $authUser->email }}.</div>
                        @if (session('status') === 'verification-link-sent')
                            <div class="small text-success mt-2">Ссылка для подтверждения отправлена повторно.</div>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('verification.send') }}" class="mt-3 mt-md-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-warning">Отправить письмо</button>
                    </form>
                </div>
            </section>
        @endif
    @endauth

    <!-- Main content -->
    <main class="content-wrapper">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Back to top button -->
    <div class="floating-buttons position-fixed top-50 end-0 z-sticky me-3 me-xl-4 pb-4">
        <a class="btn-scroll-top btn btn-sm bg-body border-0 rounded-pill shadow animate-slide-end" href="#top">
            Top
            <i class="ci-arrow-right fs-base ms-1 me-n1 animate-target"></i>
            <span class="position-absolute top-0 start-0 w-100 h-100 border rounded-pill z-0"></span>
            <svg class="position-absolute top-0 start-0 w-100 h-100 z-1" viewBox="0 0 62 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x=".75" y=".75" width="60.5" height="30.5" rx="15.25" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"/>
            </svg>
        </a>
    </div>

    <!-- Vendor scripts -->
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/choices/choices.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/nouislider/nouislider.min.js') }}"></script>


    <script src="{{ asset('assets/vendor/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/glightbox.min.js') }}"></script>


    <!-- Bootstrap + Theme scripts -->
    <script src="{{ asset('assets/js/theme.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const showFlashToast = (message, type = 'success') => {
                if (!message) {
                    return;
                }

                const container = document.querySelector('.toast-container');
                if (!container) {
                    return;
                }

                const toast = document.createElement('div');
                toast.className = `toast align-items-center text-bg-${type === 'error' ? 'danger' : 'success'} border-0`;
                toast.setAttribute('role', 'alert');
                toast.setAttribute('aria-live', 'assertive');
                toast.setAttribute('aria-atomic', 'true');
                toast.setAttribute('data-bs-delay', type === 'error' ? '4500' : '3500');
                toast.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">${message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                `;

                container.appendChild(toast);
                const instance = bootstrap.Toast.getOrCreateInstance(toast);
                toast.addEventListener('hidden.bs.toast', () => toast.remove(), { once: true });
                instance.show();
            };

            document.querySelectorAll('[data-flash-toast]').forEach((element) => {
                bootstrap.Toast.getOrCreateInstance(element).show();
            });

            document.addEventListener('click', (event) => {
                const button = event.target.closest('form[data-auto-submit="quantity"] [data-increment], form[data-auto-submit="quantity"] [data-decrement]');
                if (!button) {
                    return;
                }

                const form = button.closest('form[data-auto-submit="quantity"]');
                if (!form) {
                    return;
                }

                requestAnimationFrame(() => form.requestSubmit());
            });

            document.addEventListener('submit', async (event) => {
                const form = event.target.closest('#shoppingCart form');
                if (!form) {
                    return;
                }

                event.preventDefault();

                const submitButton = form.querySelector('[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                }

                try {
                    const response = await fetch(form.action, {
                        method: form.method || 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: new FormData(form),
                    });

                    const payload = await response.json();

                    if (!response.ok) {
                        showFlashToast(payload.message || 'Не удалось обновить корзину', 'error');
                        return;
                    }

                    const currentOffcanvas = document.getElementById('shoppingCart');
                    const currentInstance = currentOffcanvas ? bootstrap.Offcanvas.getInstance(currentOffcanvas) : null;
                    const wrapper = document.createElement('div');
                    wrapper.innerHTML = payload.offcanvas_html || '';
                    const nextOffcanvas = wrapper.firstElementChild;

                    if (currentOffcanvas && nextOffcanvas) {
                        currentOffcanvas.replaceWith(nextOffcanvas);
                        bootstrap.Offcanvas.getOrCreateInstance(nextOffcanvas).show();
                    }

                    document.querySelectorAll('[data-cart-count-badge]').forEach((badge) => {
                        badge.textContent = payload.count ?? 0;
                    });

                    if (currentInstance) {
                        currentInstance.dispose();
                    }

                    showFlashToast(payload.message || 'Корзина обновлена');
                } catch (error) {
                    showFlashToast('Не удалось обновить корзину', 'error');
                } finally {
                    if (submitButton) {
                        submitButton.disabled = false;
                    }
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
