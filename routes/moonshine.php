<?php

declare(strict_types=1);

use App\MoonShine\Pages\ImportProductsPage;
use Illuminate\Support\Facades\Route;
use UniSharp\LaravelFilemanager\Lfm;

Route::prefix('laravel-filemanager')->group(function () {
    Lfm::routes();
});

Route::prefix('import')->name('import.')->group(function () {
    Route::get('/', fn () => redirect()->route('moonshine.import.page'))->name('index');
    Route::get('/page', [ImportProductsPage::class, '__invoke'])->name('page');
    Route::post('/upload', [ImportProductsPage::class, 'upload'])->name('upload');
    Route::post('/run', [ImportProductsPage::class, 'run'])->name('run');
});
