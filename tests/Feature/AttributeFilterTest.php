<?php

namespace Tests\Feature;

use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AttributeFilterTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function category_page_loads_with_filterable_attributes(): void
    {
        $category = Category::factory()->create(['is_active' => true]);
        $product = Product::factory()->create(['category_id' => $category->id, 'is_active' => true]);
        $attr = Attribute::factory()->create(['is_filterable' => true]);
        $option = AttributeOption::factory()->create(['attribute_id' => $attr->id]);
        $product->attributeOptions()->attach($option->id);

        $response = $this->get(route('catalog.category', $category->slug));

        $response->assertStatus(200);
        $response->assertSee($attr->name);
        $response->assertSee($option->value);
    }

    #[Test]
    public function filter_by_single_attribute_option(): void
    {
        $category = Category::factory()->create(['is_active' => true]);
        $attr = Attribute::factory()->create(['is_filterable' => true]);
        $option = AttributeOption::factory()->create(['attribute_id' => $attr->id]);

        $product1 = Product::factory()->create(['category_id' => $category->id, 'is_active' => true]);
        $product2 = Product::factory()->create(['category_id' => $category->id, 'is_active' => true]);

        $product1->attributeOptions()->attach($option->id);

        $response = $this->get(route('catalog.category', [
            'slug' => $category->slug,
            'option' => [$option->id],
        ]));

        $response->assertStatus(200);
        $response->assertSee($product1->name);
        $response->assertDontSee($product2->name);
    }

    #[Test]
    public function ajax_filter_accepts_option_parameter(): void
    {
        $category = Category::factory()->create(['is_active' => true]);
        $attr = Attribute::factory()->create(['is_filterable' => true]);
        $option = AttributeOption::factory()->create(['attribute_id' => $attr->id]);

        $product = Product::factory()->create(['category_id' => $category->id, 'is_active' => true]);
        $product->attributeOptions()->attach($option->id);

        $response = $this->get(route('catalog.filter', [
            'slug' => $category->slug,
            'option' => [$option->id],
        ]));

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }
}
