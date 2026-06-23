<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_wishlist_page(): void
    {
        $response = $this->get(route('wishlist.index'));

        $response->assertStatus(200);
        $response->assertViewIs('wishlist.index');
    }

    public function test_authenticated_user_can_view_wishlist_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('wishlist.index'));

        $response->assertStatus(200);
        $response->assertViewIs('wishlist.index');
    }

    public function test_guest_can_add_product_to_wishlist(): void
    {
        $product = Product::factory()->create();

        $response = $this->postJson(route('wishlist.toggle'), [
            'product_id' => $product->id,
        ]);

        $response->assertOk();
        $response->assertJson([
            'added' => true,
            'count' => 1,
        ]);

        $this->assertDatabaseHas('wishlists', [
            'product_id' => $product->id,
            'user_id' => null,
        ]);
    }

    public function test_authenticated_user_can_add_product_to_wishlist(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->postJson(route('wishlist.toggle'), [
            'product_id' => $product->id,
        ]);

        $response->assertOk();
        $response->assertJson([
            'added' => true,
            'count' => 1,
        ]);

        $this->assertDatabaseHas('wishlists', [
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_remove_product_from_wishlist(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $wishlist = Wishlist::factory()->forUser($user)->forProduct($product)->create();

        $response = $this->actingAs($user)->postJson(route('wishlist.toggle'), [
            'product_id' => $product->id,
        ]);

        $response->assertOk();
        $response->assertJson([
            'added' => false,
            'count' => 0,
        ]);

        $this->assertDatabaseMissing('wishlists', [
            'id' => $wishlist->id,
        ]);
    }

    public function test_user_can_delete_wishlist_item(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $wishlist = Wishlist::factory()->forUser($user)->forProduct($product)->create();

        $response = $this->actingAs($user)->deleteJson(route('wishlist.remove', $wishlist));

        $response->assertOk();
        $response->assertJson([
            'count' => 0,
        ]);

        $this->assertDatabaseMissing('wishlists', [
            'id' => $wishlist->id,
        ]);
    }

    public function test_user_cannot_delete_another_user_wishlist_item(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $product = Product::factory()->create();
        $wishlist = Wishlist::factory()->forUser($user1)->forProduct($product)->create();

        $response = $this->actingAs($user2)->deleteJson(route('wishlist.remove', $wishlist));

        $response->assertNotFound();

        $this->assertDatabaseHas('wishlists', [
            'id' => $wishlist->id,
        ]);
    }

    public function test_wishlist_count_endpoint_returns_correct_count(): void
    {
        $user = User::factory()->create();
        $products = Product::factory(3)->create();

        foreach ($products as $product) {
            Wishlist::factory()->forUser($user)->forProduct($product)->create();
        }

        $response = $this->actingAs($user)->getJson(route('wishlist.count'));

        $response->assertOk();
        $response->assertJson([
            'count' => 3,
        ]);
    }

    public function test_wishlist_page_shows_user_items(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['name' => 'Test Product']);
        Wishlist::factory()->forUser($user)->forProduct($product)->create();

        $response = $this->actingAs($user)->get(route('wishlist.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Product');
    }

    public function test_wishlist_page_does_not_show_other_user_items(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $product = Product::factory()->create(['name' => 'Other User Product']);
        Wishlist::factory()->forUser($user1)->forProduct($product)->create();

        $response = $this->actingAs($user2)->get(route('wishlist.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Other User Product');
    }
}
