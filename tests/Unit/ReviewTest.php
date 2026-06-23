<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function review_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $review = Review::factory()->forUser($user)->create();

        $this->assertTrue($review->user->is($user));
    }

    #[Test]
    public function review_belongs_to_product(): void
    {
        $product = Product::factory()->create();
        $review = Review::factory()->forProduct($product)->create();

        $this->assertTrue($review->product->is($product));
    }

    #[Test]
    public function scope_approved_returns_only_approved(): void
    {
        Review::factory()->create(['is_approved' => true]);
        Review::factory()->create(['is_approved' => true]);
        Review::factory()->unapproved()->create();

        $this->assertSame(2, Review::approved()->count());
    }

    #[Test]
    public function product_average_rating_is_calculated(): void
    {
        $product = Product::factory()->create();

        Review::factory()->forProduct($product)->create(['rating' => 5]);
        Review::factory()->forProduct($product)->create(['rating' => 3]);

        $product->load('reviews');
        $avg = $product->average_rating;

        $this->assertSame(4.0, (float) $avg);
    }

    #[Test]
    public function unapproved_reviews_are_excluded_from_average(): void
    {
        $product = Product::factory()->create();

        Review::factory()->forProduct($product)->create(['rating' => 5, 'is_approved' => true]);
        Review::factory()->forProduct($product)->create(['rating' => 1, 'is_approved' => false]);

        $avg = $product->fresh()->average_rating;

        $this->assertSame(5.0, (float) $avg);
    }
}
