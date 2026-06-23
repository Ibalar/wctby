<?php

namespace Tests\Unit;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderTransitionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function transition_records_history(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::New]);

        $this->assertNull($order->status_history);

        $order->transitionTo(OrderStatus::Processing, 1);
        $order->save();

        $history = $order->fresh()->status_history;
        $this->assertIsArray($history);
        $this->assertCount(1, $history);
        $this->assertSame('new', $history[0]['from']);
        $this->assertSame('processing', $history[0]['to']);
        $this->assertSame(1, $history[0]['user_id']);
    }

    #[Test]
    public function multiple_transitions_accumulate_history(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::New]);

        $order->transitionTo(OrderStatus::Processing, 1);
        $order->save();

        $order->refresh();
        $order->transitionTo(OrderStatus::Shipped, 1);
        $order->save();

        $history = $order->fresh()->status_history;
        $this->assertCount(2, $history);
        $this->assertSame('new', $history[0]['from']);
        $this->assertSame('processing', $history[0]['to']);
        $this->assertSame('processing', $history[1]['from']);
        $this->assertSame('shipped', $history[1]['to']);
    }

    #[Test]
    public function invalid_transition_throws_exception(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::New]);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Недопустимый переход статуса');

        $order->transitionTo(OrderStatus::Delivered);
    }

    #[Test]
    public function is_cancellable_only_from_new_or_processing(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::New]);
        $this->assertTrue($order->isCancellable());

        $order->status = OrderStatus::Processing;
        $this->assertTrue($order->isCancellable());

        $order->status = OrderStatus::Shipped;
        $this->assertFalse($order->isCancellable());

        $order->status = OrderStatus::Delivered;
        $this->assertFalse($order->isCancellable());

        $order->status = OrderStatus::Completed;
        $this->assertFalse($order->isCancellable());

        $order->status = OrderStatus::Cancelled;
        $this->assertFalse($order->isCancellable());
    }

    #[Test]
    public function scope_by_status_filters_correctly(): void
    {
        $user = User::factory()->create();

        Order::factory()->create(['user_id' => $user->id, 'status' => OrderStatus::New]);
        Order::factory()->create(['user_id' => $user->id, 'status' => OrderStatus::Processing]);

        $this->assertSame(1, Order::byStatus(OrderStatus::New)->count());
        $this->assertSame(1, Order::byStatus('processing')->count());
        $this->assertSame(2, Order::byStatus([OrderStatus::New, OrderStatus::Processing])->count());
    }
}
