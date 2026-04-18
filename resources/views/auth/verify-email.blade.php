@extends('layouts.main')

@section('title', 'Подтверждение email')

@section('content')
    <section class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h3 mb-3">Подтвердите ваш email</h1>
                        <p class="text-body-secondary mb-4">
                            Перейдите по ссылке из письма. Если письмо не пришло, отправьте ссылку повторно.
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <div class="alert alert-success" role="alert">
                                Новая ссылка для подтверждения отправлена на вашу почту.
                            </div>
                        @endif

                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">Отправить письмо повторно</button>
                            </form>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary">Выйти</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
