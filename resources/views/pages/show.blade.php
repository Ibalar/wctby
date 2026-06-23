@extends('layouts.main')

@section('title', $page->title)

@section('content')
    <section class="container py-5">
        <x-breadcrumbs :items="$breadcrumbs" />
        <h1 class="h2 mb-4">{{ $page->title }}</h1>
        <div class="content">
            {!! $page->content !!}
        </div>
    </section>
@endsection
