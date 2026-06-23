<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function api_returns_products_list(): void
    {
        Product::factory()->create(['is_active' => true, 'name' => 'Test Product']);

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'current_page', 'total']);
        $response->assertJsonFragment(['name' => 'Test Product']);
    }

    #[Test]
    public function api_returns_single_product(): void
    {
        $product = Product::factory()->create(['is_active' => true]);

        $response = $this->getJson('/api/v1/products/' . $product->slug);

        $response->assertStatus(200);
        $response->assertJsonFragment(['slug' => $product->slug]);
    }

    #[Test]
    public function api_inactive_product_returns_404(): void
    {
        $product = Product::factory()->create(['is_active' => false]);

        $response = $this->getJson('/api/v1/products/' . $product->slug);

        $response->assertStatus(404);
    }

    #[Test]
    public function api_returns_categories(): void
    {
        Category::factory()->create(['is_active' => true, 'name' => 'Test Category']);

        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Test Category']);
    }

    #[Test]
    public function api_returns_single_category(): void
    {
        $category = Category::factory()->create(['is_active' => true]);

        $response = $this->getJson('/api/v1/categories/' . $category->slug);

        $response->assertStatus(200);
        $response->assertJsonFragment(['slug' => $category->slug]);
    }
}
