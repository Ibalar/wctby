<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class GuestCartMergeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cart_is_merged_after_login(): void
    {
        // Создаём гостевую корзину
        $product = Product::factory()->create(['is_active' => true]);
        $guestCart = Cart::factory()->guest()->create([
            'expires_at' => now()->addDays(7),
        ]);
        
        CartItem::factory()->create([
            'cart_id' => $guestCart->id,
            'purchasable_type' => Product::class,
            'purchasable_id' => $product->id,
            'quantity' => 2,
        ]);

        // Создаём пользователя
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Имитируем гостевую сессию
        $this->withSession(['cart_token' => $guestCart->session_token]);

        // Логинимся
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect();

        // Проверяем, что гостевая корзина удалена
        $this->assertDatabaseMissing('carts', ['id' => $guestCart->id]);

        // Проверяем, что у пользователя есть корзина с товарами
        $userCart = Cart::where('user_id', $user->id)->first();
        $this->assertNotNull($userCart);
        $this->assertEquals(1, $userCart->items()->count());
        $this->assertEquals(2, $userCart->items()->first()->quantity);
    }

    public function test_guest_cart_is_merged_after_social_login(): void
    {
        // Создаём гостевую корзину
        $product = Product::factory()->create(['is_active' => true]);
        $guestCart = Cart::factory()->guest()->create([
            'expires_at' => now()->addDays(7),
        ]);
        
        CartItem::factory()->create([
            'cart_id' => $guestCart->id,
            'purchasable_type' => Product::class,
            'purchasable_id' => $product->id,
            'quantity' => 3,
        ]);

        // Создаём пользователя
        $user = User::factory()->create();

        // Имитируем гостевую сессию
        $this->withSession(['cart_token' => $guestCart->session_token]);

        // Логиним пользователя (имитация социального входа)
        Auth::login($user);
        
        // Вызываем mergeWishlist напрямую (как это делает SocialAuthController)
        $controller = new \App\Http\Controllers\SocialAuthController(
            app(\App\Services\SocialAccountService::class)
        );
        
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('mergeWishlist');
        $method->setAccessible(true);
        $method->invoke($controller, request());

        // Проверяем, что гостевая корзина удалена
        $this->assertDatabaseMissing('carts', ['id' => $guestCart->id]);

        // Проверяем, что у пользователя есть корзина с товарами
        $userCart = Cart::where('user_id', $user->id)->first();
        $this->assertNotNull($userCart);
        $this->assertEquals(1, $userCart->items()->count());
        $this->assertEquals(3, $userCart->items()->first()->quantity);
    }

    public function test_expired_guest_cart_is_not_merged(): void
    {
        // Создаём истёкшую гостевую корзину
        $product = Product::factory()->create(['is_active' => true]);
        $guestCart = Cart::factory()->guest()->expired()->create();
        
        CartItem::factory()->create([
            'cart_id' => $guestCart->id,
            'purchasable_type' => Product::class,
            'purchasable_id' => $product->id,
            'quantity' => 2,
        ]);

        // Создаём пользователя
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Имитируем гостевую сессию
        $this->withSession(['cart_token' => $guestCart->session_token]);

        // Логинимся
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect();

        // Проверяем, что истёкшая корзина удалена
        $this->assertDatabaseMissing('carts', ['id' => $guestCart->id]);

        // Проверяем, что у пользователя нет корзины (или она пустая)
        $userCart = Cart::where('user_id', $user->id)->first();
        if ($userCart) {
            $this->assertEquals(0, $userCart->items()->count());
        }
    }

    public function test_guest_cart_items_are_merged_with_existing_user_cart(): void
    {
        // Создаём пользователя с существующей корзиной
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $product1 = Product::factory()->create(['is_active' => true]);
        $product2 = Product::factory()->create(['is_active' => true]);

        $userCart = Cart::factory()->forUser($user)->create();
        CartItem::factory()->create([
            'cart_id' => $userCart->id,
            'purchasable_type' => Product::class,
            'purchasable_id' => $product1->id,
            'quantity' => 1,
        ]);

        // Создаём гостевую корзину с тем же товаром и другим товаром
        $guestCart = Cart::factory()->guest()->create([
            'expires_at' => now()->addDays(7),
        ]);
        
        CartItem::factory()->create([
            'cart_id' => $guestCart->id,
            'purchasable_type' => Product::class,
            'purchasable_id' => $product1->id,
            'quantity' => 2,
        ]);

        CartItem::factory()->create([
            'cart_id' => $guestCart->id,
            'purchasable_type' => Product::class,
            'purchasable_id' => $product2->id,
            'quantity' => 1,
        ]);

        // Имитируем гостевую сессию
        $this->withSession(['cart_token' => $guestCart->session_token]);

        // Логинимся
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect();

        // Проверяем, что гостевая корзина удалена
        $this->assertDatabaseMissing('carts', ['id' => $guestCart->id]);

        // Проверяем, что у пользователя есть корзина с объединёнными товарами
        $userCart->refresh();
        $this->assertEquals(2, $userCart->items()->count());

        // Проверяем, что количество для product1 увеличилось (1 + 2 = 3)
        $item1 = $userCart->items()->where('purchasable_id', $product1->id)->first();
        $this->assertEquals(3, $item1->quantity);

        // Проверяем, что product2 добавлен
        $item2 = $userCart->items()->where('purchasable_id', $product2->id)->first();
        $this->assertNotNull($item2);
        $this->assertEquals(1, $item2->quantity);
    }

    public function test_session_cart_token_is_cleared_after_merge(): void
    {
        // Создаём гостевую корзину
        $product = Product::factory()->create(['is_active' => true]);
        $guestCart = Cart::factory()->guest()->create([
            'expires_at' => now()->addDays(7),
        ]);
        
        CartItem::factory()->create([
            'cart_id' => $guestCart->id,
            'purchasable_type' => Product::class,
            'purchasable_id' => $product->id,
        ]);

        // Создаём пользователя
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Имитируем гостевую сессию
        $this->withSession(['cart_token' => $guestCart->session_token]);

        // Логинимся
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect();

        // Проверяем, что cart_token удалён из сессии
        $this->assertNull(session('cart_token'));
    }
}
