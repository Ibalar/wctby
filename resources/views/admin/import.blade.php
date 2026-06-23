@extends('layouts.main')

@section('title', 'Импорт товаров')

@section('content')
    <section class="container py-5">
        <h1 class="h2 mb-4">Импорт товаров из CSV</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">CSV-файл</label>
                        <input type="file" name="file" class="form-control" accept=".csv" required>
                        <div class="form-text">
                            Колонки: name, slug, category_id, base_price, description, is_active, meta_title, meta_description
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Импортировать</button>
                    <a href="{{ route('admin.products.export') }}" class="btn btn-outline-secondary ms-2">Экспорт CSV</a>
                </form>
            </div>
        </div>
    </section>
@endsection
