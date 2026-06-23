<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Sku;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sku>
 */
class SkuFactory extends Factory
{
    protected $model = Sku::class;

    public function definition(): array
    {
        $price = fake()->randomFloat(2, 10, 5000);

        return [
            'product_id' => Product::factory(),
            'sku' => fake()->unique()->bothify('SKU-####'),
            'price' => $price,
            'old_price' => fake()->optional(0.3)->randomFloat(2, $price, $price * 1.5),
            'stock' => fake()->numberBetween(0, 100),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    public function unlimited(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => null,
        ]);
    }

    public function withDiscount(float $percent = 0.2): static
    {
        return $this->state(function (array $attributes) use ($percent) {
            $price = $attributes['price'] ?? fake()->randomFloat(2, 10, 5000);
            return [
                'price' => $price,
                'old_price' => round($price / (1 - $percent), 2),
            ];
        });
    }

    public function forProduct(Product $product): static
    {
        return $this->state(fn (array $attributes) => [
            'product_id' => $product->id,
        ]);
    }
}
