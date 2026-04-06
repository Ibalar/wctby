@extends('layouts.main')

@section('title', 'Мои адреса')

@section('content')
    @php
        $shippingAddresses = $addresses->where('type', 'shipping')->values();
        $billingAddresses = $addresses->where('type', 'billing')->values();
        $storeFields = ['type', 'city', 'street', 'house', 'apartment', 'postal_code', 'is_default'];
        $editAddressId = old('address_id');
        $hasCreateErrors = $errors->hasAny($storeFields) && ! $editAddressId;
    @endphp

    <div class="container py-5 mt-n2 mt-sm-0">
        <div class="row pt-md-2 pt-lg-3 pb-sm-2 pb-md-3 pb-lg-4 pb-xl-5">
            <aside class="col-lg-3">
                <div class="offcanvas-lg offcanvas-start pe-lg-0 pe-xl-4" id="accountSidebar">
                    <div class="offcanvas-header d-lg-block py-3 p-lg-0">
                        <div class="d-flex align-items-center">
                            <img
                                src="{{ $user->avatar_url }}"
                                alt="{{ $user->display_name }}"
                                class="rounded-circle object-fit-cover flex-shrink-0"
                                width="48"
                                height="48"
                            >
                            <div class="min-w-0 ps-3">
                                <h5 class="h6 mb-1 text-truncate">{{ $user->full_name_middle ?: $user->name }}</h5>
                                <div class="nav flex-nowrap text-nowrap min-w-0">
                                    <span class="nav-link text-body p-0">
                                        <i class="ci-mail fs-sm opacity-75 me-2"></i>
                                        <span class="text-truncate">{{ $user->email }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-close d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#accountSidebar" aria-label="Close"></button>
                    </div>

                    <div class="offcanvas-body d-block pt-2 pt-lg-4 pb-lg-0">
                        <div class="row row-cols-3 g-3 mb-4 pb-2">
                            <div class="col">
                                <div class="border rounded-4 text-center p-3 h-100">
                                    <div class="fs-xl fw-semibold text-dark-emphasis">{{ $totalOrders }}</div>
                                    <div class="fs-xs text-body-secondary">Заказов</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border rounded-4 text-center p-3 h-100">
                                    <div class="fs-xl fw-semibold text-dark-emphasis">{{ number_format($totalSpent, 0, '.', ' ') }}</div>
                                    <div class="fs-xs text-body-secondary">Потрачено</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border rounded-4 text-center p-3 h-100">
                                    <div class="fs-xl fw-semibold text-dark-emphasis">{{ $addresses->count() }}</div>
                                    <div class="fs-xs text-body-secondary">Адресов</div>
                                </div>
                            </div>
                        </div>

                        <h6 class="pt-1 ps-2 ms-1">Покупки</h6>
                        <nav class="list-group list-group-borderless">
                            <a class="list-group-item list-group-item-action d-flex align-items-center" href="{{ route('profile.orders') }}">
                                <i class="ci-shopping-bag fs-base opacity-75 me-2"></i>
                                Заказы
                                <span class="badge bg-primary rounded-pill ms-auto">{{ $totalOrders }}</span>
                            </a>
                            <a class="list-group-item list-group-item-action d-flex align-items-center" href="{{ route('cart.index') }}">
                                <i class="ci-shopping-cart fs-base opacity-75 me-2"></i>
                                Корзина
                            </a>
                        </nav>

                        <h6 class="pt-4 ps-2 ms-1">Управление аккаунтом</h6>
                        <nav class="list-group list-group-borderless">
                            <a class="list-group-item list-group-item-action d-flex align-items-center" href="{{ route('profile.index') }}">
                                <i class="ci-user fs-base opacity-75 me-2"></i>
                                Личные данные
                            </a>
                            <a class="list-group-item list-group-item-action d-flex align-items-center active" href="{{ route('profile.addresses') }}">
                                <i class="ci-map-pin fs-base opacity-75 me-2"></i>
                                Адреса
                            </a>
                            <a class="list-group-item list-group-item-action d-flex align-items-center" href="{{ route('profile.social') }}">
                                <i class="ci-link fs-base opacity-75 me-2"></i>
                                Социальные аккаунты
                            </a>
                        </nav>

                        <h6 class="pt-4 ps-2 ms-1">Действия</h6>
                        <nav class="list-group list-group-borderless">
                            <a class="list-group-item list-group-item-action d-flex align-items-center" href="{{ route('catalog.index') }}">
                                <i class="ci-grid fs-base opacity-75 me-2"></i>
                                Каталог
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="list-group-item list-group-item-action d-flex align-items-center w-100 text-start border-0 bg-transparent">
                                    <i class="ci-log-out fs-base opacity-75 me-2"></i>
                                    Выйти
                                </button>
                            </form>
                        </nav>
                    </div>
                </div>
            </aside>

            <div class="col-lg-9">
                <div class="ps-lg-3 ps-xl-0">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 pb-1 pb-sm-2">
                        <div>
                            <h1 class="h2 mb-1">Адреса</h1>
                            <p class="text-body-secondary mb-0">Сохранённые адреса доставки и реквизиты для оформления заказов.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a class="btn btn-outline-secondary d-lg-none" href="#accountSidebar" data-bs-toggle="offcanvas" aria-controls="accountSidebar">
                                <i class="ci-sidebar fs-base me-2"></i>
                                Меню кабинета
                            </a>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newAddressModal">
                                <i class="ci-plus fs-base me-2"></i>
                                Новый адрес
                            </button>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4" role="alert">
                            <div class="fw-semibold mb-2">Проверьте данные адреса.</div>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @forelse ($shippingAddresses as $address)
                        @php
                            $isEditOpen = (string) $editAddressId === (string) $address->id;
                            $sectionClass = $address->is_default ? 'primary-address-'.$address->id : 'shipping-address-'.$address->id;
                        @endphp
                        <div class="border-bottom py-4">
                            <div class="nav flex-nowrap align-items-center justify-content-between pb-1 mb-3">
                                <div class="d-flex align-items-center gap-3 me-4 flex-wrap">
                                    <h2 class="h6 mb-0">{{ $address->is_default ? 'Основной адрес доставки' : 'Дополнительный адрес доставки' }}</h2>
                                    @if ($address->is_default)
                                        <span class="badge text-bg-info rounded-pill">Основной</span>
                                    @endif
                                </div>
                                <a class="nav-link hiding-collapse-toggle text-decoration-underline p-0 {{ $isEditOpen ? '' : 'collapsed' }}" href=".{{ $sectionClass }}" data-bs-toggle="collapse" aria-expanded="{{ $isEditOpen ? 'true' : 'false' }}" aria-controls="addressPreview{{ $address->id }} addressEdit{{ $address->id }}">Изменить</a>
                            </div>

                            <div class="collapse {{ $sectionClass }} {{ $isEditOpen ? '' : 'show' }}" id="addressPreview{{ $address->id }}">
                                <ul class="list-unstyled fs-sm m-0">
                                    <li>{{ $address->postal_code ? $address->postal_code.', ' : '' }}{{ $address->city }}</li>
                                    <li>{{ $address->street }}@if($address->house), д. {{ $address->house }}@endif @if($address->apartment), кв. {{ $address->apartment }}@endif</li>
                                </ul>
                                <div class="d-flex gap-2 flex-wrap pt-3">
                                    @unless ($address->is_default)
                                        <form method="POST" action="{{ route('profile.addresses.default', $address) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">Сделать основным</button>
                                        </form>
                                    @endunless
                                    <form method="POST" action="{{ route('profile.addresses.destroy', $address) }}" onsubmit="return confirm('Удалить адрес?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Удалить</button>
                                    </form>
                                </div>
                            </div>

                            <div class="collapse {{ $sectionClass }} {{ $isEditOpen ? 'show' : '' }}" id="addressEdit{{ $address->id }}">
                                <form class="row g-3 g-sm-4" method="POST" action="{{ route('profile.addresses.update', $address) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="address_id" value="{{ $address->id }}">
                                    <input type="hidden" name="type" value="shipping">
                                    <div class="col-sm-6">
                                        <label for="city_{{ $address->id }}" class="form-label">Город</label>
                                        <input type="text" class="form-control" id="city_{{ $address->id }}" name="city" value="{{ $isEditOpen ? old('city') : $address->city }}" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="postal_code_{{ $address->id }}" class="form-label">Почтовый индекс</label>
                                        <input type="text" class="form-control" id="postal_code_{{ $address->id }}" name="postal_code" value="{{ $isEditOpen ? old('postal_code') : $address->postal_code }}">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="street_{{ $address->id }}" class="form-label">Улица</label>
                                        <input type="text" class="form-control" id="street_{{ $address->id }}" name="street" value="{{ $isEditOpen ? old('street') : $address->street }}" required>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="house_{{ $address->id }}" class="form-label">Дом</label>
                                        <input type="text" class="form-control" id="house_{{ $address->id }}" name="house" value="{{ $isEditOpen ? old('house') : $address->house }}">
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="apartment_{{ $address->id }}" class="form-label">Квартира</label>
                                        <input type="text" class="form-control" id="apartment_{{ $address->id }}" name="apartment" value="{{ $isEditOpen ? old('apartment') : $address->apartment }}">
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex gap-3 pt-2 pt-sm-0 flex-wrap">
                                            <button type="submit" class="btn btn-primary">Сохранить</button>
                                            <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target=".{{ $sectionClass }}" aria-expanded="true" aria-controls="addressPreview{{ $address->id }} addressEdit{{ $address->id }}">Закрыть</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="border rounded-5 p-4 text-center text-body-secondary mb-4">
                            Адреса доставки пока не добавлены.
                        </div>
                    @endforelse

                    @forelse ($billingAddresses as $address)
                        @php
                            $isEditOpen = (string) $editAddressId === (string) $address->id;
                            $sectionClass = $address->is_default ? 'primary-billing-'.$address->id : 'billing-address-'.$address->id;
                        @endphp
                        <div class="border-bottom py-4">
                            <div class="nav flex-nowrap align-items-center justify-content-between pb-1 mb-3">
                                <div class="d-flex align-items-center gap-3 me-4 flex-wrap">
                                    <h2 class="h6 mb-0">{{ $address->is_default ? 'Основной платёжный адрес' : 'Дополнительный платёжный адрес' }}</h2>
                                    @if ($address->is_default)
                                        <span class="badge text-bg-info rounded-pill">Основной</span>
                                    @endif
                                </div>
                                <a class="nav-link hiding-collapse-toggle text-decoration-underline p-0 {{ $isEditOpen ? '' : 'collapsed' }}" href=".{{ $sectionClass }}" data-bs-toggle="collapse" aria-expanded="{{ $isEditOpen ? 'true' : 'false' }}" aria-controls="billingPreview{{ $address->id }} billingEdit{{ $address->id }}">Изменить</a>
                            </div>

                            <div class="collapse {{ $sectionClass }} {{ $isEditOpen ? '' : 'show' }}" id="billingPreview{{ $address->id }}">
                                <ul class="list-unstyled fs-sm m-0">
                                    <li>{{ $address->postal_code ? $address->postal_code.', ' : '' }}{{ $address->city }}</li>
                                    <li>{{ $address->street }}@if($address->house), д. {{ $address->house }}@endif @if($address->apartment), кв. {{ $address->apartment }}@endif</li>
                                </ul>
                                <div class="d-flex gap-2 flex-wrap pt-3">
                                    @unless ($address->is_default)
                                        <form method="POST" action="{{ route('profile.addresses.default', $address) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">Сделать основным</button>
                                        </form>
                                    @endunless
                                    <form method="POST" action="{{ route('profile.addresses.destroy', $address) }}" onsubmit="return confirm('Удалить адрес?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Удалить</button>
                                    </form>
                                </div>
                            </div>

                            <div class="collapse {{ $sectionClass }} {{ $isEditOpen ? 'show' : '' }}" id="billingEdit{{ $address->id }}">
                                <form class="row g-3 g-sm-4" method="POST" action="{{ route('profile.addresses.update', $address) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="address_id" value="{{ $address->id }}">
                                    <input type="hidden" name="type" value="billing">
                                    <div class="col-sm-6">
                                        <label for="billing_city_{{ $address->id }}" class="form-label">Город</label>
                                        <input type="text" class="form-control" id="billing_city_{{ $address->id }}" name="city" value="{{ $isEditOpen ? old('city') : $address->city }}" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="billing_postal_code_{{ $address->id }}" class="form-label">Почтовый индекс</label>
                                        <input type="text" class="form-control" id="billing_postal_code_{{ $address->id }}" name="postal_code" value="{{ $isEditOpen ? old('postal_code') : $address->postal_code }}">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="billing_street_{{ $address->id }}" class="form-label">Улица</label>
                                        <input type="text" class="form-control" id="billing_street_{{ $address->id }}" name="street" value="{{ $isEditOpen ? old('street') : $address->street }}" required>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="billing_house_{{ $address->id }}" class="form-label">Дом</label>
                                        <input type="text" class="form-control" id="billing_house_{{ $address->id }}" name="house" value="{{ $isEditOpen ? old('house') : $address->house }}">
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="billing_apartment_{{ $address->id }}" class="form-label">Квартира</label>
                                        <input type="text" class="form-control" id="billing_apartment_{{ $address->id }}" name="apartment" value="{{ $isEditOpen ? old('apartment') : $address->apartment }}">
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex gap-3 pt-2 pt-sm-0 flex-wrap">
                                            <button type="submit" class="btn btn-primary">Сохранить</button>
                                            <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target=".{{ $sectionClass }}" aria-expanded="true" aria-controls="billingPreview{{ $address->id }} billingEdit{{ $address->id }}">Закрыть</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="border rounded-5 p-4 text-center text-body-secondary mt-4">
                            Платёжные адреса пока не добавлены.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newAddressModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="newAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newAddressModalLabel">Новый адрес</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 g-lg-4" method="POST" action="{{ route('profile.addresses.store') }}">
                        @csrf
                        <div class="col-lg-6">
                            <label for="new_address_type" class="form-label">Тип адреса</label>
                            <select class="form-select" id="new_address_type" name="type" required>
                                <option value="shipping" @selected(old('type', 'shipping') === 'shipping')>Для доставки</option>
                                <option value="billing" @selected(old('type') === 'billing')>Для документов и оплаты</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="new_postal_code" class="form-label">Почтовый индекс</label>
                            <input type="text" class="form-control" id="new_postal_code" name="postal_code" value="{{ old('postal_code') }}">
                        </div>
                        <div class="col-lg-6">
                            <label for="new_city" class="form-label">Город</label>
                            <input type="text" class="form-control" id="new_city" name="city" value="{{ old('city') }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="new_street" class="form-label">Улица</label>
                            <input type="text" class="form-control" id="new_street" name="street" value="{{ old('street') }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="new_house" class="form-label">Дом</label>
                            <input type="text" class="form-control" id="new_house" name="house" value="{{ old('house') }}">
                        </div>
                        <div class="col-lg-6">
                            <label for="new_apartment" class="form-label">Квартира / офис</label>
                            <input type="text" class="form-control" id="new_apartment" name="apartment" value="{{ old('apartment') }}">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="new_is_default" name="is_default" value="1" @checked(old('is_default'))>
                                <label class="form-check-label" for="new_is_default">
                                    Сделать адрес основным для выбранного типа
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex gap-3 flex-wrap">
                                <button type="submit" class="btn btn-primary">Сохранить адрес</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="fixed-bottom z-sticky w-100 btn btn-lg btn-dark border-0 border-top border-light border-opacity-10 rounded-0 pb-4 d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#accountSidebar" aria-controls="accountSidebar" data-bs-theme="light">
        <i class="ci-sidebar fs-base me-2"></i>
        Меню кабинета
    </button>
@endsection

@push('scripts')
    @if ($hasCreateErrors)
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modalElement = document.getElementById('newAddressModal');
                if (modalElement) {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            });
        </script>
    @endif
@endpush
