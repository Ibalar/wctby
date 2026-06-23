<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authenticated_user_can_create_review(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->postJson(route('reviews.store'), [
            'product_id' => $product->id,
            'rating' => 5,
            'title' => 'Отличный товар',
            'body' => 'Всё понравилось, рекомендую!',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 5,
        ]);
    }

    #[Test]
    public function guest_cannot_create_review(): void
    {
        $product = Product::factory()->create();

        $response = $this->postJson(route('reviews.store'), [
            'product_id' => $product->id,
            'rating' => 5,
            'body' => 'Отзыв от гостя',
        ]);

        $response->assertStatus(401);
    }

    #[Test]
    public function review_requires_valid_product(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('reviews.store'), [
            'product_id' => 9999,
            'rating' => 5,
            'body' => 'Невалидный товар',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['product_id']);
    }

    #[Test]
    public function rating_must_be_between_1_and_5(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->postJson(route('reviews.store'), [
            'product_id' => $product->id,
            'rating' => 6,
            'body' => 'Слишком высокая оценка',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['rating']);
    }

    #[Test]
    public function user_can_update_existing_review(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        Review::factory()->forUser($user)->forProduct($product)->create([
            'rating' => 3,
            'body' => 'Старый отзыв',
        ]);

        $this->assertSame(1, Review::count());

        $response = $this->actingAs($user)->postJson(route('reviews.store'), [
            'product_id' => $product->id,
            'rating' => 5,
            'body' => 'Обновлённый отзыв',
        ]);

        $response->assertStatus(200);

        $this->assertSame(1, Review::count());
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 5,
            'body' => 'Обновлённый отзыв',
        ]);
    }
}
