<?php

use Illuminate\Support\Facades\Route;
use UniSharp\LaravelFilemanager\Lfm;

Route::prefix('laravel-filemanager')->group(function () {
    Lfm::routes();
});

Route::get('/', function () {
    return view('welcome');
});
