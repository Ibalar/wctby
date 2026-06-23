<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('SAVE##')),
            'type' => 'percent',
            'value' => fake()->numberBetween(5, 50),
            'is_active' => true,
        ];
    }

    public function percent(): static
    {
        return $this->state(['type' => 'percent']);
    }

    public function fixed(): static
    {
        return $this->state(['type' => 'fixed']);
    }

    public function expired(): static
    {
        return $this->state(['expires_at' => now()->subDay()]);
    }
}
