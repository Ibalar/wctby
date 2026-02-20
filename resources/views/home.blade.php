@extends('layouts.main')

@section('title', 'WCT.BY - Главная')

@section('meta_description', 'Интернет-магазин электроники, смартфонов, компьютеров и аксессуаров по выгодным ценам')
@section('meta_keywords', 'электроника, смартфоны, компьютеры, наушники, планшеты, купить')

@section('content')
    <!-- Hero Slider -->
    <x-slider :slides="$slides" />

    <!-- Features -->
    <section class="container pt-5 mt-1 mt-sm-3 mt-lg-4">
        <div class="row row-cols-2 row-cols-md-4 g-4">
            <!-- Item -->
            <div class="col">
                <div class="d-flex flex-column flex-xxl-row align-items-center">
                    <div class="d-flex text-dark-emphasis bg-body-tertiary rounded-circle p-4 mb-3 mb-xxl-0">
                        <i class="ci-delivery fs-2 m-xxl-1"></i>
                    </div>
                    <div class="text-center text-xxl-start ps-xxl-3">
                        <h3 class="h6 mb-1">Бесплатная доставка</h3>
                        <p class="fs-sm mb-0">Для заказов от 200 BYN</p>
                    </div>
                </div>
            </div>

            <!-- Item -->
            <div class="col">
                <div class="d-flex flex-column flex-xxl-row align-items-center">
                    <div class="d-flex text-dark-emphasis bg-body-tertiary rounded-circle p-4 mb-3 mb-xxl-0">
                        <i class="ci-credit-card fs-2 m-xxl-1"></i>
                    </div>
                    <div class="text-center text-xxl-start ps-xxl-3">
                        <h3 class="h6 mb-1">Безопасная оплата</h3>
                        <p class="fs-sm mb-0">100% защита платежей</p>
                    </div>
                </div>
            </div>

            <!-- Item -->
            <div class="col">
                <div class="d-flex flex-column flex-xxl-row align-items-center">
                    <div class="d-flex text-dark-emphasis bg-body-tertiary rounded-circle p-4 mb-3 mb-xxl-0">
                        <i class="ci-refresh-cw fs-2 m-xxl-1"></i>
                    </div>
                    <div class="text-center text-xxl-start ps-xxl-3">
                        <h3 class="h6 mb-1">Возврат средств</h3>
                        <p class="fs-sm mb-0">В течение 30 дней</p>
                    </div>
                </div>
            </div>

            <!-- Item -->
            <div class="col">
                <div class="d-flex flex-column flex-xxl-row align-items-center">
                    <div class="d-flex text-dark-emphasis bg-body-tertiary rounded-circle p-4 mb-3 mb-xxl-0">
                        <i class="ci-chat fs-2 m-xxl-1"></i>
                    </div>
                    <div class="text-center text-xxl-start ps-xxl-3">
                        <h3 class="h6 mb-1">Поддержка 24/7</h3>
                        <p class="fs-sm mb-0">Круглосуточная помощь</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular categories -->
    <section class="container pt-5 mt-1 mt-sm-2 mt-md-3 mt-lg-4">
        <h2 class="h3 pb-2 pb-sm-3">Популярные категории</h2>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
            <div class="col">
                <a href="#" class="card h-100 text-decoration-none">
                    <div class="card-body text-center">
                        <i class="ci-smartphone fs-1 text-primary mb-3"></i>
                        <h5 class="card-title h6">Смартфоны</h5>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="#" class="card h-100 text-decoration-none">
                    <div class="card-body text-center">
                        <i class="ci-laptop fs-1 text-primary mb-3"></i>
                        <h5 class="card-title h6">Ноутбуки</h5>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="#" class="card h-100 text-decoration-none">
                    <div class="card-body text-center">
                        <i class="ci-headphones fs-1 text-primary mb-3"></i>
                        <h5 class="card-title h6">Наушники</h5>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="#" class="card h-100 text-decoration-none">
                    <div class="card-body text-center">
                        <i class="ci-watch fs-1 text-primary mb-3"></i>
                        <h5 class="card-title h6">Умные часы</h5>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="#" class="card h-100 text-decoration-none">
                    <div class="card-body text-center">
                        <i class="ci-tablet fs-1 text-primary mb-3"></i>
                        <h5 class="card-title h6">Планшеты</h5>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="#" class="card h-100 text-decoration-none">
                    <div class="card-body text-center">
                        <i class="ci-monitor fs-1 text-primary mb-3"></i>
                        <h5 class="card-title h6">Мониторы</h5>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="bg-body-tertiary py-5 mt-5">
        <div class="container pt-sm-2 pt-md-3 pt-lg-4 pt-xl-5">
            <div class="row">
                <div class="col-md-6 col-lg-5 mb-5 mb-md-0">
                    <h2 class="h4 mb-2">Подпишитесь на рассылку</h2>
                    <p class="text-body pb-2 pb-ms-3">Получайте первыми информацию о скидках и новинках</p>
                    <form class="d-flex needs-validation pb-1 pb-sm-2 pb-md-3 pb-lg-0 mb-4 mb-lg-5" novalidate>
                        <div class="position-relative w-100 me-2">
                            <input type="email" class="form-control form-control-lg" placeholder="Ваш email" required>
                        </div>
                        <button type="submit" class="btn btn-lg btn-primary">Подписаться</button>
                    </form>
                    <div class="d-flex gap-3">
                        <a class="btn btn-icon btn-secondary rounded-circle" href="#" aria-label="Instagram">
                            <i class="ci-instagram fs-base"></i>
                        </a>
                        <a class="btn btn-icon btn-secondary rounded-circle" href="#" aria-label="Facebook">
                            <i class="ci-facebook fs-base"></i>
                        </a>
                        <a class="btn btn-icon btn-secondary rounded-circle" href="#" aria-label="YouTube">
                            <i class="ci-youtube fs-base"></i>
                        </a>
                        <a class="btn btn-icon btn-secondary rounded-circle" href="#" aria-label="Telegram">
                            <i class="ci-telegram fs-base"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
