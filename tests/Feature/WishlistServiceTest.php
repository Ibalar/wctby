<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use App\Services\WishlistService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class WishlistServiceTest extends TestCase
{
    use RefreshDatabase;

    protected WishlistService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(WishlistService::class);
    }

    public function test_merge_guest_wishlist_transfers_items_to_user(): void
    {
        $user = User::factory()->create();
        $sessionToken = Str::random(40);
        $products = Product::factory(3)->create();

        foreach ($products as $product) {
            Wishlist::factory()->forGuest()->forProduct($product)->create([
                'session_token' => $sessionToken,
            ]);
        }

        $this->assertDatabaseCount('wishlists', 3);

        $this->service->mergeGuestWishlist($user, $sessionToken);

        $this->assertDatabaseCount('wishlists', 3);
        
        foreach ($products as $product) {
            $this->assertDatabaseHas('wishlists', [
                'user_id' => $user->id,
                'product_id' => $product->id,
                'session_token' => null,
            ]);
        }
    }

    public function test_merge_guest_wishlist_does_not_create_duplicates(): void
    {
        $user = User::factory()->create();
        $sessionToken = Str::random(40);
        $product = Product::factory()->create();

        Wishlist::factory()->forUser($user)->forProduct($product)->create();
        Wishlist::factory()->forGuest()->forProduct($product)->create([
            'session_token' => $sessionToken,
        ]);

        $this->assertDatabaseCount('wishlists', 2);

        $this->service->mergeGuestWishlist($user, $sessionToken);

        $this->assertDatabaseCount('wishlists', 1);
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_merge_guest_wishlist_deletes_guest_items(): void
    {
        $user = User::factory()->create();
        $sessionToken = Str::random(40);
        $product = Product::factory()->create();

        Wishlist::factory()->forGuest()->forProduct($product)->create([
            'session_token' => $sessionToken,
        ]);

        $this->service->mergeGuestWishlist($user, $sessionToken);

        $this->assertDatabaseMissing('wishlists', [
            'session_token' => $sessionToken,
        ]);
    }

    public function test_merge_guest_wishlist_does_not_affect_other_sessions(): void
    {
        $user = User::factory()->create();
        $sessionToken1 = Str::random(40);
        $sessionToken2 = Str::random(40);
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        Wishlist::factory()->forGuest()->forProduct($product1)->create([
            'session_token' => $sessionToken1,
        ]);
        Wishlist::factory()->forGuest()->forProduct($product2)->create([
            'session_token' => $sessionToken2,
        ]);

        $this->service->mergeGuestWishlist($user, $sessionToken1);

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product1->id,
        ]);

        $this->assertDatabaseHas('wishlists', [
            'session_token' => $sessionToken2,
            'product_id' => $product2->id,
        ]);
    }

    public function test_merge_guest_wishlist_handles_empty_session(): void
    {
        $user = User::factory()->create();
        $sessionToken = Str::random(40);

        $this->service->mergeGuestWishlist($user, $sessionToken);

        $this->assertDatabaseCount('wishlists', 0);
    }
}
