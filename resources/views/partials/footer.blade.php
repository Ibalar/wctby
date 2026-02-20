<!-- Page footer -->
<footer class="footer position-relative bg-dark">
    <span class="position-absolute top-0 start-0 w-100 h-100 bg-body d-none d-block-dark"></span>
    <div class="container position-relative z-1 pt-sm-2 pt-md-3 pt-lg-4" data-bs-theme="dark">

        <!-- Columns with links that are turned into accordion on screens < 500px wide (sm breakpoint) -->
        <div class="accordion py-5" id="footerLinks">
            <div class="row">
                <div class="col-md-4 d-sm-flex flex-md-column align-items-center align-items-md-start pb-3 mb-sm-4">
                    <h4 class="mb-sm-0 mb-md-4 me-4">
                        <a class="text-dark-emphasis text-decoration-none" href="{{ url('/') }}">{{ config('app.name', 'WCT.BY') }}</a>
                    </h4>
                    <p class="text-body fs-sm text-sm-end text-md-start mb-sm-0 mb-md-3 ms-0 ms-sm-auto ms-md-0 me-4">Есть вопросы? Свяжитесь с нами 24/7</p>
                    <div class="dropdown" style="max-width: 250px">
                        <button type="button" class="btn btn-light dropdown-toggle justify-content-between w-100 d-none-dark" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Помощь и консультация
                        </button>
                        <button type="button" class="btn btn-secondary dropdown-toggle justify-content-between w-100 d-none d-flex-dark" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Помощь и консультация
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Центр помощи и FAQ</a></li>
                            <li><a class="dropdown-item" href="#">Чат поддержки</a></li>
                            <li><a class="dropdown-item" href="#">Создать тикет</a></li>
                            <li><a class="dropdown-item" href="#">Колл-центр</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row row-cols-1 row-cols-sm-3 gx-3 gx-md-4">
                        <div class="accordion-item col border-0">
                            <h6 class="accordion-header" id="companyHeading">
                                <span class="text-dark-emphasis d-none d-sm-block">Компания</span>
                                <button type="button" class="accordion-button collapsed py-3 d-sm-none" data-bs-toggle="collapse" data-bs-target="#companyLinks" aria-expanded="false" aria-controls="companyLinks">Компания</button>
                            </h6>
                            <div class="accordion-collapse collapse d-sm-block" id="companyLinks" aria-labelledby="companyHeading" data-bs-parent="#footerLinks">
                                <ul class="nav flex-column gap-2 pt-sm-3 pb-3 mt-n1 mb-1">
                                    <li class="d-flex w-100 pt-1">
                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="#">О компании</a>
                                    </li>
                                    <li class="d-flex w-100 pt-1">
                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="#">Наша команда</a>
                                    </li>
                                    <li class="d-flex w-100 pt-1">
                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="#">Вакансии</a>
                                    </li>
                                    <li class="d-flex w-100 pt-1">
                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="#">Контакты</a>
                                    </li>
                                    <li class="d-flex w-100 pt-1">
                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="#">Новости</a>
                                    </li>
                                </ul>
                            </div>
                            <hr class="d-sm-none my-0">
                        </div>
                        <div class="accordion-item col border-0">
                            <h6 class="accordion-header" id="accountHeading">
                                <span class="text-dark-emphasis d-none d-sm-block">Аккаунт</span>
                                <button type="button" class="accordion-button collapsed py-3 d-sm-none" data-bs-toggle="collapse" data-bs-target="#accountLinks" aria-expanded="false" aria-controls="accountLinks">Аккаунт</button>
                            </h6>
                            <div class="accordion-collapse collapse d-sm-block" id="accountLinks" aria-labelledby="accountHeading" data-bs-parent="#footerLinks">
                                <ul class="nav flex-column gap-2 pt-sm-3 pb-3 mt-n1 mb-1">
                                    <li class="d-flex w-100 pt-1">
                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="#">Ваш аккаунт</a>
                                    </li>
                                    <li class="d-flex w-100 pt-1">
                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="#">Тарифы и условия доставки</a>
                                    </li>
                                    <li class="d-flex w-100 pt-1">
                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="#">Возврат и замена</a>
                                    </li>
                                    <li class="d-flex w-100 pt-1">
                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="#">Информация о доставке</a>
                                    </li>
                                    <li class="d-flex w-100 pt-1">
                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="#">Отслеживание заказа</a>
                                    </li>
                                    <li class="d-flex w-100 pt-1">
                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="#">Налоги и сборы</a>
                                    </li>
                                </ul>
                            </div>
                            <hr class="d-sm-none my-0">
                        </div>
                        <div class="accordion-item col border-0">
                            <h6 class="accordion-header" id="customerHeading">
                                <span class="text-dark-emphasis d-none d-sm-block">Клиентский сервис</span>
                                <button type="button" class="accordion-button collapsed py-3 d-sm-none" data-bs-toggle="collapse" data-bs-target="#customerLinks" aria-expanded="false" aria-controls="customerLinks">Клиентский сервис</button>
                            </h6>
                            <div class="accordion-collapse collapse d-sm-block" id="customerLinks" aria-labelledby="customerHeading" data-bs-parent="#footerLinks">
                                <ul class="nav flex-column gap-2 pt-sm-3 pb-3 mt-n1 mb-1">
                                    <li class="d-flex w-100 pt-1">
                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="#">Способы оплаты</a>
                                    </li>
                                    <li class="d-flex w-100 pt-1">
                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="#">Гарантия возврата</a>
                                    </li>
                                    <li class="d-flex w-100 pt-1">
                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="#">Возврат товаров</a>
                                    </li>
                                    <li class="d-flex w-100 pt-1">
                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="#">Центр поддержки</a>
                                    </li>
                                    <li class="d-flex w-100 pt-1">
                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="#">Доставка</a>
                                    </li>
                                    <li class="d-flex w-100 pt-1">
                                        <a class="nav-link animate-underline animate-target d-inline fw-normal text-truncate p-0" href="#">Условия использования</a>
                                    </li>
                                </ul>
                            </div>
                            <hr class="d-sm-none my-0">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category / tag links -->
        <div class="d-flex flex-column gap-3 pb-3 pb-md-4 pb-lg-5 mt-n2 mt-sm-n4 mt-lg-0 mb-4">
            <ul class="nav align-items-center text-body-tertiary gap-2">
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Компьютеры</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Смартфоны</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">ТВ, видео</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Колонки</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Камеры</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Принтеры</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Видеоигры</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Наушники</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Умные часы</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">SSD/HDD</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Умный дом</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Apple</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Планшеты</a>
                </li>
            </ul>
            <ul class="nav align-items-center text-body-tertiary gap-2">
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Мониторы</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Сканеры</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Серверы</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Климатическая техника</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Электронные книги</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Хранение данных</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Сетевое оборудование</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Удлинители</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Розетки</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Датчики</a>
                </li>
                <li class="px-1">/</li>
                <li class="animate-underline">
                    <a class="nav-link fw-normal p-0 animate-target" href="#">Аксессуары</a>
                </li>
            </ul>
        </div>

        <!-- Copyright + Payment methods -->
        <div class="d-md-flex align-items-center border-top py-4">
            <div class="d-flex gap-2 gap-sm-3 justify-content-center ms-md-auto mb-4 mb-md-0 order-md-2">
                <div>
                    <img src="{{ asset('assets/img/payment-methods/visa-dark-mode.svg') }}" alt="Visa">
                </div>
                <div>
                    <img src="{{ asset('assets/img/payment-methods/mastercard.svg') }}" alt="Mastercard">
                </div>
                <div>
                    <img src="{{ asset('assets/img/payment-methods/paypal-dark-mode.svg') }}" alt="PayPal">
                </div>
                <div>
                    <img src="{{ asset('assets/img/payment-methods/google-pay-dark-mode.svg') }}" alt="Google Pay">
                </div>
                <div>
                    <img src="{{ asset('assets/img/payment-methods/apple-pay-dark-mode.svg') }}" alt="Apple Pay">
                </div>
            </div>
            <p class="text-body fs-xs text-center text-md-start mb-0 me-4 order-md-1">&copy; {{ date('Y') }} {{ config('app.name', 'WCT.BY') }}. Все права защищены.</p>
        </div>
    </div>
</footer>
