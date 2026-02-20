<?php

use Illuminate\Support\Facades\Route;
use UniSharp\LaravelFilemanager\Lfm;

Route::prefix('laravel-filemanager')->group(function () {
    Lfm::routes();
});

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);
