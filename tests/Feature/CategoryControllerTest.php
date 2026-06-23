<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function category_page_shows_products(): void
    {
        $category = Category::factory()->create(['is_active' => true]);
        Product::factory()->create(['category_id' => $category->id, 'is_active' => true, 'name' => 'Test Product']);

        $response = $this->get(route('catalog.category', $category->slug));

        $response->assertStatus(200);
        $response->assertSee('Test Product');
    }

    #[Test]
    public function inactive_category_returns_404(): void
    {
        $category = Category::factory()->create(['is_active' => false]);

        $response = $this->get(route('catalog.category', $category->slug));

        $response->assertStatus(404);
    }

    #[Test]
    public function catalog_index_shows_categories(): void
    {
        Category::factory()->create(['is_active' => true, 'name' => 'Electronics']);

        $response = $this->get(route('catalog.index'));

        $response->assertStatus(200);
        $response->assertSee('Electronics');
    }

    #[Test]
    public function category_filter_by_status(): void
    {
        $category = Category::factory()->create(['is_active' => true]);
        Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'flags' => [['title' => 'Хит', 'active' => true]],
            'name' => 'Hit Product',
        ]);
        Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'name' => 'Regular Product',
        ]);

        $response = $this->get(route('catalog.category', ['slug' => $category->slug, 'status' => ['Хит']]));

        $response->assertStatus(200);
        $response->assertSee('Hit Product');
        $response->assertDontSee('Regular Product');
    }
}
