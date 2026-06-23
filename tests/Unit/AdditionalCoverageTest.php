<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function category_tree_is_cached(): void
    {
        Category::factory()->create(['is_active' => true, 'name' => 'Root']);
        $service = app(\App\Services\CategoryService::class);

        $tree1 = $service->getTree();
        $tree2 = $service->getTree();

        $this->assertCount(1, $tree1);
        $this->assertEquals($tree1->first()->name, $tree2->first()->name);
    }

    #[Test]
    public function product_model_has_average_rating(): void
    {
        $product = Product::factory()->create();

        \App\Models\Review::factory()->create(['product_id' => $product->id, 'rating' => 5, 'is_approved' => true]);
        \App\Models\Review::factory()->create(['product_id' => $product->id, 'rating' => 3, 'is_approved' => true]);

        $this->assertEquals(4.0, (float) $product->fresh()->average_rating);
    }

    #[Test]
    public function search_controller_finds_products(): void
    {
        Product::factory()->create(['is_active' => true, 'name' => 'UniqueProductName']);

        $response = $this->get(route('search', ['q' => 'UniqueProductName']));

        $response->assertStatus(200);
        $response->assertSee('UniqueProductName');
    }

    #[Test]
    public function bundle_model_has_items(): void
    {
        $bundle = \App\Models\Bundle::factory()->create(['is_active' => true]);
        $product = Product::factory()->create();
        \App\Models\BundleItem::factory()->create(['bundle_id' => $bundle->id, 'product_id' => $product->id]);

        $this->assertCount(1, $bundle->fresh()->items);
    }
}
