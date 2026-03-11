<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use UniSharp\LaravelFilemanager\Lfm;

Route::prefix('laravel-filemanager')->group(function () {
    Lfm::routes();
});

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/catalog', [CategoryController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{slug}', [CategoryController::class, 'show'])->name('catalog.category');

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('catalog.product');
