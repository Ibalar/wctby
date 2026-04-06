<?php

namespace App\Providers;

use App\Http\Responses\LoginResponse;
use App\Http\Responses\PasswordResetResponse;
use App\Http\Responses\RegisterResponse;
use App\Http\Responses\TwoFactorLoginResponse;
use App\Models\Category;
use App\Services\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\PasswordResetResponse as PasswordResetResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(RegisterResponseContract::class, RegisterResponse::class);
        $this->app->singleton(TwoFactorLoginResponseContract::class, TwoFactorLoginResponse::class);
        $this->app->singleton(PasswordResetResponseContract::class, PasswordResetResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $categories = Category::with('children')
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            $view->with('categories', $categories);
        });

        View::composer(['partials.header', 'partials.cart-offcanvas'], function ($view) {
            $request = request();

            if (!$request || !$request->hasSession()) {
                $view->with('cartItems', collect())
                    ->with('cartCount', 0)
                    ->with('cartTotal', 0)
                    ->with('cartSavings', 0);

                return;
            }

            $cartService = app(CartService::class);
            $cart = $cartService->getOrCreateCart($request);

            $view->with('cartItems', $cartService->getItems($cart))
                ->with('cartCount', $cartService->getItemsCount($cart))
                ->with('cartTotal', $cartService->getTotal($cart))
                ->with('cartSavings', $cartService->getSavings($cart));
        });
    }
}
