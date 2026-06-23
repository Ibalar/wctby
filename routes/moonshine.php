<?php

declare(strict_types=1);

use App\MoonShine\Pages\ImportProductsPage;
use Illuminate\Support\Facades\Route;
use UniSharp\LaravelFilemanager\Lfm;

Route::prefix('laravel-filemanager')->group(function () {
    Lfm::routes();
});

Route::prefix('import')->group(function () {
    Route::get('/', [ImportProductsPage::class, '__invoke']);
    Route::post('/upload', [ImportProductsPage::class, 'upload']);
    Route::post('/run', [ImportProductsPage::class, 'run']);
});
