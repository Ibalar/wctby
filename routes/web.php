<?php

use App\Http\Controllers\AdminImportExportController;
use App\Http\Controllers\BundleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Админ: импорт/экспорт товаров
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/products/export', [AdminImportExportController::class, 'export'])->name('products.export');
    Route::get('/products/import', [AdminImportExportController::class, 'showForm'])->name('products.import.form');
    Route::post('/products/import', [AdminImportExportController::class, 'import'])->name('products.import');
});

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::prefix('catalog')->group(function () {

    Route::get('/', [CategoryController::class, 'index'])->name('catalog.index');

    Route::get('{slug}/filter', [CategoryController::class, 'filter'])
        ->name('catalog.filter');

    Route::get('{slug}', [CategoryController::class, 'show'])
        ->name('catalog.category');
});

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('catalog.product');

Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

// Корзина
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/data', [CartController::class, 'data'])->name('cart.data');
});

// Избранное
Route::middleware('throttle:60,1')->prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('index');
    Route::post('/toggle', [WishlistController::class, 'toggle'])->name('toggle');
    Route::delete('/{wishlist}', [WishlistController::class, 'remove'])->name('remove');
    Route::get('/count', [WishlistController::class, 'count'])->name('count');
});

// Отзывы
Route::middleware(['auth', 'throttle:10,1'])->prefix('reviews')->name('reviews.')->group(function () {
    Route::post('/', [ReviewController::class, 'store'])->name('store');
});

// Бандлы (комплекты)
Route::get('/bundles', [BundleController::class, 'index'])->name('bundles.index');
Route::get('/bundle/{slug}', [BundleController::class, 'show'])->name('bundles.show');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->middleware(['throttle:10,1', 'verified.if.auth'])->name('checkout.process');
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

Route::middleware('throttle:20,1')->group(function () {
    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
        ->name('social.redirect');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])
        ->name('social.callback');
});

Route::middleware('auth')->group(function () {
    Route::delete('/auth/{provider}/unlink', [SocialAuthController::class, 'unlink'])
        ->name('social.unlink');
});

Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/avatar', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
    Route::get('/security', [ProfileController::class, 'security'])->name('security');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::get('/social', [ProfileController::class, 'socialAccounts'])->name('social');
    Route::delete('/social/{provider}', [ProfileController::class, 'unlinkSocial'])->name('social.unlink');

    Route::middleware('verified')->group(function () {
        Route::get('/orders', [ProfileController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [ProfileController::class, 'orderShow'])->name('order');

        Route::get('/addresses', [AddressController::class, 'index'])->name('addresses');
        Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
        Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
        Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
        Route::post('/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');
    });
});
