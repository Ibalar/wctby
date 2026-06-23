<?php

namespace Tests\Feature;

use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CouponTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function valid_coupon_can_be_applied(): void
    {
        Coupon::factory()->create(['code' => 'SALE10', 'type' => 'percent', 'value' => 10]);

        $response = $this->post(route('coupon.apply'), ['code' => 'SALE10']);

        $response->assertRedirect();
        $this->assertEquals('SALE10', session('coupon.code'));
    }

    #[Test]
    public function invalid_coupon_shows_error(): void
    {
        $response = $this->post(route('coupon.apply'), ['code' => 'INVALID']);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    #[Test]
    public function percent_coupon_calculates_discount(): void
    {
        $coupon = Coupon::factory()->percent()->create(['value' => 15]);

        $discount = $coupon->calculateDiscount(100);

        $this->assertEquals(15.0, $discount);
    }

    #[Test]
    public function fixed_coupon_calculates_discount(): void
    {
        $coupon = Coupon::factory()->fixed()->create(['value' => 20]);

        $discount = $coupon->calculateDiscount(100);

        $this->assertEquals(20.0, $discount);
    }

    #[Test]
    public function expired_coupon_is_invalid(): void
    {
        $coupon = Coupon::factory()->expired()->create();

        $this->assertFalse($coupon->isValid());
    }
}
