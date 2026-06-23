<?php

namespace Database\Factories;

use App\Models\DeliveryMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeliveryMethod>
 */
class DeliveryMethodFactory extends Factory
{
    protected $model = DeliveryMethod::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Самовывоз',
                'Курьер по городу',
                'Почта Беларуси',
                'Европочта',
                'Белпочта (до двери)',
            ]),
            'code' => fake()->unique()->bothify('delivery_####'),
            'description' => fake()->optional()->sentence(),
            'price' => fake()->randomFloat(2, 0, 20),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }

    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => 0,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
