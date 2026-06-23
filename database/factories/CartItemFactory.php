<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition(): array
    {
        $product = Product::factory()->create();
        
        return [
            'cart_id' => Cart::factory(),
            'purchasable_type' => Product::class,
            'purchasable_id' => $product->id,
            'quantity' => $this->faker->numberBetween(1, 5),
            'price' => $product->base_price,
        ];
    }

    public function forCart(Cart $cart): static
    {
        return $this->state(fn (array $attributes) => [
            'cart_id' => $cart->id,
        ]);
    }

    public function forProduct(Product $product): static
    {
        return $this->state(fn (array $attributes) => [
            'purchasable_type' => Product::class,
            'purchasable_id' => $product->id,
            'price' => $product->base_price,
        ]);
    }
}
