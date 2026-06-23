<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class CartExpirationTest extends TestCase
{
    use RefreshDatabase;

    protected CartService $cartService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartService = app(CartService::class);
    }

    public function test_expired_guest_cart_is_cleared_on_access(): void
    {
        $expiredCart = Cart::factory()->create([
            'user_id' => null,
            'session_token' => 'expired_session',
            'expires_at' => now()->subDay(),
        ]);

        $product = Product::factory()->create();
        CartItem::factory()->create([
            'cart_id' => $expiredCart->id,
            'purchasable_type' => Product::class,
            'purchasable_id' => $product->id,
        ]);

        $request = Request::create('/cart', 'GET');
        $request->setLaravelSession(app('session.store'));
        $request->session()->put('cart_token', 'expired_session');

        $cart = $this->cartService->getOrCreateCart($request);

        $this->assertDatabaseMissing('carts', ['id' => $expiredCart->id]);
        $this->assertDatabaseMissing('cart_items', ['cart_id' => $expiredCart->id]);
        $this->assertNotEquals($expiredCart->id, $cart->id);
        $this->assertTrue($cart->expires_at->isFuture());
    }

    public function test_active_guest_cart_is_not_cleared(): void
    {
        $activeCart = Cart::factory()->create([
            'user_id' => null,
            'session_token' => 'active_session',
            'expires_at' => now()->addDays(7),
        ]);

        $product = Product::factory()->create();
        CartItem::factory()->create([
            'cart_id' => $activeCart->id,
            'purchasable_type' => Product::class,
            'purchasable_id' => $product->id,
        ]);

        $request = Request::create('/cart', 'GET');
        $request->setLaravelSession(app('session.store'));
        $request->session()->put('cart_token', 'active_session');

        $cart = $this->cartService->getOrCreateCart($request);

        $this->assertDatabaseHas('carts', ['id' => $activeCart->id]);
        $this->assertDatabaseHas('cart_items', ['cart_id' => $activeCart->id]);
        $this->assertEquals($activeCart->id, $cart->id);
    }

    public function test_expired_guest_cart_is_not_merged_on_login(): void
    {
        $user = User::factory()->create();
        
        $expiredCart = Cart::factory()->create([
            'user_id' => null,
            'session_token' => 'expired_session',
            'expires_at' => now()->subDay(),
        ]);

        $product = Product::factory()->create();
        CartItem::factory()->create([
            'cart_id' => $expiredCart->id,
            'purchasable_type' => Product::class,
            'purchasable_id' => $product->id,
        ]);

        $this->cartService->mergeGuestCart($user, 'expired_session');

        $this->assertDatabaseMissing('carts', ['id' => $expiredCart->id]);
        $this->assertDatabaseMissing('cart_items', ['cart_id' => $expiredCart->id]);
        
        $userCart = Cart::where('user_id', $user->id)->first();
        $this->assertNull($userCart);
    }

    public function test_active_guest_cart_is_merged_on_login(): void
    {
        $user = User::factory()->create();
        
        $activeCart = Cart::factory()->create([
            'user_id' => null,
            'session_token' => 'active_session',
            'expires_at' => now()->addDays(7),
        ]);

        $product = Product::factory()->create();
        CartItem::factory()->create([
            'cart_id' => $activeCart->id,
            'purchasable_type' => Product::class,
            'purchasable_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->cartService->mergeGuestCart($user, 'active_session');

        $this->assertDatabaseMissing('carts', ['id' => $activeCart->id]);
        
        $userCart = Cart::where('user_id', $user->id)->first();
        $this->assertNotNull($userCart);
        $this->assertEquals(1, $userCart->items()->count());
        $this->assertEquals(2, $userCart->items()->first()->quantity);
    }

    public function test_is_expired_returns_true_for_expired_cart(): void
    {
        $expiredCart = Cart::factory()->create([
            'expires_at' => now()->subDay(),
        ]);

        $this->assertTrue($this->cartService->isExpired($expiredCart));
    }

    public function test_is_expired_returns_false_for_active_cart(): void
    {
        $activeCart = Cart::factory()->create([
            'expires_at' => now()->addDays(7),
        ]);

        $this->assertFalse($this->cartService->isExpired($activeCart));
    }

    public function test_is_expired_returns_false_for_cart_without_expiration(): void
    {
        $cart = Cart::factory()->create([
            'expires_at' => null,
        ]);

        $this->assertFalse($this->cartService->isExpired($cart));
    }

    public function test_new_guest_cart_has_expiration_date(): void
    {
        $request = Request::create('/cart', 'GET');
        $request->setLaravelSession(app('session.store'));

        $cart = $this->cartService->getOrCreateCart($request);

        $this->assertNotNull($cart->expires_at);
        $this->assertTrue($cart->expires_at->isFuture());
        $this->assertTrue($cart->expires_at->diffInDays(now()) <= 7);
    }
}
