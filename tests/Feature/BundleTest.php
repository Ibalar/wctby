<?php

namespace Tests\Feature;

use App\Models\Bundle;
use App\Models\BundleItem;
use App\Models\Product;
use App\Models\Sku;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BundleControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function bundles_list_shows_active_bundles(): void
    {
        $bundle = Bundle::factory()->create(['is_active' => true, 'name' => 'Test Bundle']);
        Bundle::factory()->create(['is_active' => false, 'name' => 'Hidden']);

        $response = $this->get(route('bundles.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Bundle');
        $response->assertDontSee('Hidden');
    }

    #[Test]
    public function bundle_show_displays_details(): void
    {
        $bundle = Bundle::factory()->create(['is_active' => true, 'total_price' => 199.99]);
        $product = Product::factory()->create(['is_active' => true]);
        BundleItem::factory()->create(['bundle_id' => $bundle->id, 'product_id' => $product->id, 'quantity' => 2]);

        $response = $this->get(route('bundles.show', $bundle->slug));

        $response->assertStatus(200);
        $response->assertSee($bundle->name);
        $response->assertSee('199.99');
        $response->assertSee($product->name);
    }

    #[Test]
    public function inactive_bundle_returns_404(): void
    {
        $bundle = Bundle::factory()->create(['is_active' => false]);

        $response = $this->get(route('bundles.show', $bundle->slug));

        $response->assertStatus(404);
    }
}

class BundleCartTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function bundle_can_be_added_to_cart(): void
    {
        $user = User::factory()->create();
        $bundle = Bundle::factory()->create(['is_active' => true, 'total_price' => 150]);

        $response = $this->actingAs($user)->postJson(route('cart.add'), [
            'purchasable_type' => 'bundle',
            'purchasable_id' => $bundle->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(200);

        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertNotNull($cart);

        $item = $cart->items->first();
        $this->assertNotNull($item);
        $this->assertSame(150.0, (float) $item->price);
    }
}
