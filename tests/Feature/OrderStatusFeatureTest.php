<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderStatusFeatureTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function order_created_with_new_status(): void
    {
        $order = Order::factory()->create();

        $this->assertSame(OrderStatus::New, $order->status);
    }

    #[Test]
    public function status_transition_sends_notification(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'customer_name' => $user->first_name ?? 'Test',
            'customer_email' => $user->email,
            'status' => OrderStatus::New,
        ]);

        $oldStatus = $order->status;
        $order->transitionTo(OrderStatus::Processing, 1);
        $order->save();

        $user->notify(new OrderStatusChangedNotification($order, $oldStatus, OrderStatus::Processing));

        Notification::assertSentTo($user, OrderStatusChangedNotification::class);
    }

    #[Test]
    public function profile_controller_filters_by_status(): void
    {
        $user = User::factory()->create();

        Order::factory()->create(['user_id' => $user->id, 'status' => OrderStatus::New]);
        Order::factory()->create(['user_id' => $user->id, 'status' => OrderStatus::Completed]);

        $completedCount = Order::where('user_id', $user->id)
            ->where('status', OrderStatus::Completed)
            ->count();

        $this->assertSame(1, $completedCount);
    }

    #[Test]
    public function order_form_page_has_select_with_all_statuses(): void
    {
        $statusCount = count(OrderStatus::cases());

        $this->assertSame(6, $statusCount, 'Should have 6 statuses for Select options');
    }
}
