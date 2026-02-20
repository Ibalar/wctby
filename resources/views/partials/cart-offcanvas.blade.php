<div class="offcanvas offcanvas-end pb-sm-2 px-sm-2" id="shoppingCart" tabindex="-1" aria-labelledby="shoppingCartLabel" style="width: 500px">
    <!-- Header -->
    <div class="offcanvas-header flex-column align-items-start py-3 pt-lg-4">
        <div class="d-flex align-items-center justify-content-between w-100 mb-3 mb-lg-4">
            <h4 class="offcanvas-title" id="shoppingCartLabel">Корзина</h4>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <p class="fs-sm">Купите ещё на <span class="text-dark-emphasis fw-semibold">$183</span> для получения <span class="text-dark-emphasis fw-semibold">бесплатной доставки</span></p>
        <div class="progress w-100" role="progressbar" aria-label="Прогресс бесплатной доставки" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="height: 4px">
            <div class="progress-bar bg-warning rounded-pill" style="width: 75%"></div>
        </div>
    </div>

    <!-- Items -->
    <div class="offcanvas-body d-flex flex-column gap-4 pt-2">
        <!-- Item -->
        <div class="d-flex align-items-center">
            <a class="flex-shrink-0" href="#">
                <img src="{{ asset('assets/img/shop/electronics/thumbs/08.png') }}" width="110" alt="iPhone 14">
            </a>
            <div class="w-100 min-w-0 ps-2 ps-sm-3">
                <h5 class="d-flex animate-underline mb-2">
                    <a class="d-block fs-sm fw-medium text-truncate animate-target" href="#">Apple iPhone 14 128GB White</a>
                </h5>
                <div class="h6 pb-1 mb-2">$899.00</div>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="count-input rounded-2">
                        <button type="button" class="btn btn-icon btn-sm" data-decrement aria-label="Уменьшить количество">
                            <i class="ci-minus"></i>
                        </button>
                        <input type="number" class="form-control form-control-sm" value="1" readonly>
                        <button type="button" class="btn btn-icon btn-sm" data-increment aria-label="Увеличить количество">
                            <i class="ci-plus"></i>
                        </button>
                    </div>
                    <button type="button" class="btn-close fs-sm" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-sm" data-bs-title="Удалить" aria-label="Удалить из корзины"></button>
                </div>
            </div>
        </div>

        <!-- Item -->
        <div class="d-flex align-items-center">
            <a class="position-relative flex-shrink-0" href="#">
                <span class="badge text-bg-danger position-absolute top-0 start-0">-10%</span>
                <img src="{{ asset('assets/img/shop/electronics/thumbs/09.png') }}" width="110" alt="iPad Pro">
            </a>
            <div class="w-100 min-w-0 ps-2 ps-sm-3">
                <h5 class="d-flex animate-underline mb-2">
                    <a class="d-block fs-sm fw-medium text-truncate animate-target" href="#">Tablet Apple iPad Pro M2</a>
                </h5>
                <div class="h6 pb-1 mb-2">$989.00 <del class="text-body-tertiary fs-xs fw-normal">$1,099.00</del></div>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="count-input rounded-2">
                        <button type="button" class="btn btn-icon btn-sm" data-decrement aria-label="Уменьшить количество">
                            <i class="ci-minus"></i>
                        </button>
                        <input type="number" class="form-control form-control-sm" value="1" readonly>
                        <button type="button" class="btn btn-icon btn-sm" data-increment aria-label="Увеличить количество">
                            <i class="ci-plus"></i>
                        </button>
                    </div>
                    <button type="button" class="btn-close fs-sm" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-sm" data-bs-title="Удалить" aria-label="Удалить из корзины"></button>
                </div>
            </div>
        </div>

        <!-- Item -->
        <div class="d-flex align-items-center">
            <a class="flex-shrink-0" href="#">
                <img src="{{ asset('assets/img/shop/electronics/thumbs/01.png') }}" width="110" alt="Smart Watch">
            </a>
            <div class="w-100 min-w-0 ps-2 ps-sm-3">
                <h5 class="d-flex animate-underline mb-2">
                    <a class="d-block fs-sm fw-medium text-truncate animate-target" href="#">Smart Watch Series 7, White</a>
                </h5>
                <div class="h6 pb-1 mb-2">$429.00</div>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="count-input rounded-2">
                        <button type="button" class="btn btn-icon btn-sm" data-decrement aria-label="Уменьшить количество">
                            <i class="ci-minus"></i>
                        </button>
                        <input type="number" class="form-control form-control-sm" value="1" readonly>
                        <button type="button" class="btn btn-icon btn-sm" data-increment aria-label="Увеличить количество">
                            <i class="ci-plus"></i>
                        </button>
                    </div>
                    <button type="button" class="btn-close fs-sm" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-sm" data-bs-title="Удалить" aria-label="Удалить из корзины"></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="offcanvas-header flex-column align-items-start">
        <div class="d-flex align-items-center justify-content-between w-100 mb-3 mb-md-4">
            <span class="text-light-emphasis">Итого:</span>
            <span class="h6 mb-0">$2,317.00</span>
        </div>
        <div class="d-flex w-100 gap-3">
            <a class="btn btn-lg btn-secondary w-100" href="#">В корзину</a>
            <a class="btn btn-lg btn-primary w-100" href="#">Оформить заказ</a>
        </div>
    </div>
</div>
