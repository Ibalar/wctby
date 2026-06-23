<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Sku;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $price = fake()->randomFloat(2, 10, 5000);
        $quantity = fake()->numberBetween(1, 5);

        return [
            'order_id' => Order::factory(),
            'item_type' => Product::class,
            'item_id' => Product::factory(),
            'name' => fake()->words(3, true),
            'sku' => fake()->optional()->bothify('SKU-####'),
            'price' => $price,
            'quantity' => $quantity,
            'line_total' => $price * $quantity,
            'meta' => null,
        ];
    }

    public function forOrder(Order $order): static
    {
        return $this->state(fn (array $attributes) => [
            'order_id' => $order->id,
        ]);
    }

    public function forProduct(Product $product): static
    {
        return $this->state(fn (array $attributes) => [
            'item_type' => Product::class,
            'item_id' => $product->id,
            'name' => $product->name,
        ]);
    }

    public function forSku(Sku $sku): static
    {
        return $this->state(fn (array $attributes) => [
            'item_type' => Sku::class,
            'item_id' => $sku->id,
            'name' => $sku->product?->name ?? 'SKU Product',
            'sku' => $sku->sku,
            'price' => $sku->price,
        ]);
    }
}
