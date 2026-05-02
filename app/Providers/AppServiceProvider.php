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
use RuntimeException;

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
        $this->guardDestructiveDatabaseCommands();

        View::composer('partials.header', function ($view) {
            static $categories = null;

            if ($categories === null) {
                $categories = Category::with([
                        'children' => fn ($query) => $query
                            ->where('is_active', true)
                            ->orderBy('sort_order')
                            ->with('promoProduct:id,slug'),
                        'promoProduct:id,slug',
                    ])
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
            }

            $view->with('categories', $categories);
        });

        View::composer(['partials.header', 'partials.cart-offcanvas'], function ($view) {
            static $summary = null;
            static $summaryCartId = null;

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
            $cartId = (int) $cart->id;

            if ($summary === null || $summaryCartId !== $cartId) {
                $summary = $cartService->getSummary($cart);
                $summaryCartId = $cartId;
            }

            $view->with('cartItems', $summary['items'])
                ->with('cartCount', $summary['count'])
                ->with('cartTotal', $summary['total'])
                ->with('cartSavings', $summary['savings']);
        });
    }

    private function guardDestructiveDatabaseCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        if ($this->canRunDestructiveCommandSafely()) {
            return;
        }

        $argv = $_SERVER['argv'] ?? [];
        $command = $argv[1] ?? null;
        $destructiveCommands = [
            'migrate:fresh',
            'migrate:refresh',
            'migrate:reset',
            'db:wipe',
        ];

        if (is_string($command) && in_array($command, $destructiveCommands, true)) {
            throw new RuntimeException(
                "Blocked command '{$command}'. Set ALLOW_DESTRUCTIVE_DB_COMMANDS=true ".
                "explicitly for intentional destructive DB operations."
            );
        }

        if (
            $command === 'test'
            && (in_array('--drop-databases', $argv, true) || in_array('--recreate-databases', $argv, true))
        ) {
            throw new RuntimeException(
                "Blocked 'test' with destructive DB options. ".
                "Set ALLOW_DESTRUCTIVE_DB_COMMANDS=true for intentional use."
            );
        }
    }

    private function canRunDestructiveCommandSafely(): bool
    {
        return filter_var(env('ALLOW_DESTRUCTIVE_DB_COMMANDS', false), FILTER_VALIDATE_BOOL);
    }
}
