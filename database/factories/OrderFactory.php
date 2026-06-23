<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 50, 10000);
        $shippingAmount = fake()->randomFloat(2, 0, 30);
        $discountAmount = 0;
        $total = $subtotal + $shippingAmount - $discountAmount;

        return [
            'user_id' => User::factory(),
            'number' => 'ORD-' . now()->format('Ymd') . '-' . str_pad((string) fake()->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),
            'status' => 'new',
            'currency' => 'BYN',
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'shipping_amount' => $shippingAmount,
            'total' => $total,
            'payment_method' => 'cash',
            'delivery_method' => 'pickup',
            'payment_method_code' => 'cash',
            'payment_method_name' => 'Наличные',
            'delivery_method_code' => 'pickup',
            'delivery_method_name' => 'Самовывоз',
            'delivery_price' => $shippingAmount,
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->phoneNumber(),
            'customer_email' => fake()->optional()->safeEmail(),
            'shipping_address' => [
                'city' => fake()->city(),
                'street' => fake()->streetName(),
                'house' => (string) fake()->buildingNumber(),
                'apartment' => fake()->optional()->secondaryAddress(),
            ],
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'processing',
        ]);
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
        ]);
    }
}
