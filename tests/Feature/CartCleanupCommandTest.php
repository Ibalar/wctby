<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartCleanupCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_deletes_expired_guest_carts(): void
    {
        $expiredCart = Cart::factory()->create([
            'user_id' => null,
            'session_token' => 'expired_session',
            'expires_at' => now()->subDay(),
        ]);

        CartItem::factory()->create(['cart_id' => $expiredCart->id]);

        $this->artisan('cart:cleanup')
            ->expectsOutput('Cleaning up expired guest carts...')
            ->expectsOutput('Found 1 expired guest carts.')
            ->expectsOutput('Successfully deleted 1 expired guest carts and their items.')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('carts', ['id' => $expiredCart->id]);
        $this->assertDatabaseMissing('cart_items', ['cart_id' => $expiredCart->id]);
    }

    public function test_command_does_not_delete_active_guest_carts(): void
    {
        $activeCart = Cart::factory()->create([
            'user_id' => null,
            'session_token' => 'active_session',
            'expires_at' => now()->addDays(7),
        ]);

        CartItem::factory()->create(['cart_id' => $activeCart->id]);

        $this->artisan('cart:cleanup')
            ->expectsOutput('No expired guest carts found.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('carts', ['id' => $activeCart->id]);
        $this->assertDatabaseHas('cart_items', ['cart_id' => $activeCart->id]);
    }

    public function test_command_does_not_delete_user_carts(): void
    {
        $userCart = Cart::factory()->create([
            'user_id' => 1,
            'session_token' => 'user_session',
            'expires_at' => now()->subDay(),
        ]);

        $this->artisan('cart:cleanup')
            ->expectsOutput('No expired guest carts found.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('carts', ['id' => $userCart->id]);
    }

    public function test_command_deletes_multiple_expired_carts(): void
    {
        Cart::factory()->count(5)->create([
            'user_id' => null,
            'expires_at' => now()->subDays(2),
        ]);

        Cart::factory()->count(3)->create([
            'user_id' => null,
            'expires_at' => now()->addDays(5),
        ]);

        $this->artisan('cart:cleanup')
            ->expectsOutput('Found 5 expired guest carts.')
            ->expectsOutput('Successfully deleted 5 expired guest carts and their items.')
            ->assertExitCode(0);

        $this->assertEquals(3, Cart::count());
    }
}
