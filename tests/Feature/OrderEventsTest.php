<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use App\Listeners\ClearCart;
use App\Listeners\SendOrderConfirmation;
use App\Listeners\SendNewOrderAdminNotification;
use App\Listeners\SendOrderStatusChangedNotification;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderAdminNotification;
use App\Notifications\OrderConfirmationNotification;
use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderEventsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function order_created_event_is_dispatched_with_listeners(): void
    {
        Event::fake();

        $user = User::factory()->create();
        $order = Order::factory()->forUser($user)->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        OrderCreated::dispatch($order, $cart);

        Event::assertDispatched(OrderCreated::class, fn ($event) =>
            $event->order->id === $order->id && $event->cart->id === $cart->id
        );

        Event::assertListening(OrderCreated::class, ClearCart::class);
        Event::assertListening(OrderCreated::class, SendOrderConfirmation::class);
        Event::assertListening(OrderCreated::class, SendNewOrderAdminNotification::class);
    }

    #[Test]
    public function order_status_changed_event_is_dispatched_with_listener(): void
    {
        Event::fake();

        $order = Order::factory()->create(['status' => OrderStatus::New]);
        $oldStatus = $order->status;
        $newStatus = OrderStatus::Processing;

        OrderStatusChanged::dispatch($order, $oldStatus, $newStatus);

        Event::assertDispatched(OrderStatusChanged::class, fn ($event) =>
            $event->order->id === $order->id
            && $event->oldStatus === $oldStatus
            && $event->newStatus === $newStatus
        );

        Event::assertListening(OrderStatusChanged::class, SendOrderStatusChangedNotification::class);
    }

    #[Test]
    public function notifications_are_sent_on_order_created(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $order = Order::factory()->forUser($user)->create([
            'customer_name' => 'Test Customer',
            'customer_email' => $user->email,
        ]);
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        $listener = new SendOrderConfirmation;
        $listener->handle(new OrderCreated($order, $cart));

        Notification::assertSentTo($user, OrderConfirmationNotification::class);
    }

    #[Test]
    public function admin_notification_sent_on_order_created(): void
    {
        Notification::fake();

        config(['mail.admin_email' => 'admin@test.com']);

        $order = Order::factory()->guest()->create([
            'customer_name' => 'Guest',
            'customer_email' => 'guest@test.com',
        ]);
        $cart = Cart::factory()->create();

        $listener = new SendNewOrderAdminNotification;
        $listener->handle(new OrderCreated($order, $cart));

        Notification::assertSentOnDemand(NewOrderAdminNotification::class);
    }

    #[Test]
    public function order_transition_dispatches_status_changed_event(): void
    {
        Event::fake([OrderStatusChanged::class]);

        $order = Order::factory()->create(['status' => OrderStatus::New]);
        $order->transitionTo(OrderStatus::Processing, 1);
        $order->save();

        Event::assertDispatched(OrderStatusChanged::class, fn ($event) =>
            $event->order->id === $order->id
            && $event->oldStatus === OrderStatus::New
            && $event->newStatus === OrderStatus::Processing
        );
    }
}
