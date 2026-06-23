<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class WishlistModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_wishlist_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $wishlist = Wishlist::factory()->forUser($user)->forProduct($product)->create();

        $this->assertInstanceOf(User::class, $wishlist->user);
        $this->assertEquals($user->id, $wishlist->user->id);
    }

    public function test_wishlist_belongs_to_product(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $wishlist = Wishlist::factory()->forUser($user)->forProduct($product)->create();

        $this->assertInstanceOf(Product::class, $wishlist->product);
        $this->assertEquals($product->id, $wishlist->product->id);
    }

    public function test_wishlist_can_be_created_for_guest(): void
    {
        $product = Product::factory()->create();
        $sessionToken = Str::random(40);

        $wishlist = Wishlist::create([
            'session_token' => $sessionToken,
            'product_id' => $product->id,
        ]);

        $this->assertDatabaseHas('wishlists', [
            'id' => $wishlist->id,
            'session_token' => $sessionToken,
            'product_id' => $product->id,
            'user_id' => null,
        ]);
    }

    public function test_wishlist_can_be_created_for_user(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $wishlist = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertDatabaseHas('wishlists', [
            'id' => $wishlist->id,
            'user_id' => $user->id,
            'product_id' => $product->id,
            'session_token' => null,
        ]);
    }

    public function test_wishlist_user_product_unique_constraint(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_wishlist_session_product_unique_constraint(): void
    {
        $product = Product::factory()->create();
        $sessionToken = Str::random(40);

        Wishlist::create([
            'session_token' => $sessionToken,
            'product_id' => $product->id,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Wishlist::create([
            'session_token' => $sessionToken,
            'product_id' => $product->id,
        ]);
    }

    public function test_wishlist_can_have_same_product_for_different_users(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $product = Product::factory()->create();

        $wishlist1 = Wishlist::create([
            'user_id' => $user1->id,
            'product_id' => $product->id,
        ]);

        $wishlist2 = Wishlist::create([
            'user_id' => $user2->id,
            'product_id' => $product->id,
        ]);

        $this->assertDatabaseHas('wishlists', ['id' => $wishlist1->id]);
        $this->assertDatabaseHas('wishlists', ['id' => $wishlist2->id]);
    }

    public function test_wishlist_can_have_same_product_for_different_sessions(): void
    {
        $product = Product::factory()->create();
        $sessionToken1 = Str::random(40);
        $sessionToken2 = Str::random(40);

        $wishlist1 = Wishlist::create([
            'session_token' => $sessionToken1,
            'product_id' => $product->id,
        ]);

        $wishlist2 = Wishlist::create([
            'session_token' => $sessionToken2,
            'product_id' => $product->id,
        ]);

        $this->assertDatabaseHas('wishlists', ['id' => $wishlist1->id]);
        $this->assertDatabaseHas('wishlists', ['id' => $wishlist2->id]);
    }

    public function test_wishlist_is_deleted_when_user_is_deleted(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $wishlist = Wishlist::factory()->forUser($user)->forProduct($product)->create();

        $user->delete();

        $this->assertDatabaseMissing('wishlists', ['id' => $wishlist->id]);
    }

    public function test_wishlist_is_deleted_when_product_is_deleted(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $wishlist = Wishlist::factory()->forUser($user)->forProduct($product)->create();

        $product->delete();

        $this->assertDatabaseMissing('wishlists', ['id' => $wishlist->id]);
    }
}
