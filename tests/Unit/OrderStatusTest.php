<?php

namespace Tests\Unit;

use App\Enums\OrderStatus;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    #[Test]
    public function all_statuses_have_label_and_color(): void
    {
        foreach (OrderStatus::cases() as $status) {
            $this->assertNotEmpty($status->label(), "Status {$status->value} has no label");
            $this->assertNotEmpty($status->color(), "Status {$status->value} has no color");
        }
    }

    #[Test]
    public function allowed_transitions_follow_workflow(): void
    {
        // New → Processing, Cancelled
        $this->assertEqualsCanonicalizing(
            [OrderStatus::Processing, OrderStatus::Cancelled],
            OrderStatus::New->allowedTransitions(),
        );

        // Processing → Shipped, Cancelled
        $this->assertEqualsCanonicalizing(
            [OrderStatus::Shipped, OrderStatus::Cancelled],
            OrderStatus::Processing->allowedTransitions(),
        );

        // Shipped → Delivered
        $this->assertEquals(
            [OrderStatus::Delivered],
            OrderStatus::Shipped->allowedTransitions(),
        );

        // Delivered → Completed
        $this->assertEquals(
            [OrderStatus::Completed],
            OrderStatus::Delivered->allowedTransitions(),
        );

        // Completed → none
        $this->assertEmpty(OrderStatus::Completed->allowedTransitions());

        // Cancelled → none
        $this->assertEmpty(OrderStatus::Cancelled->allowedTransitions());
    }

    #[Test]
    public function can_transition_to_returns_correct_result(): void
    {
        $this->assertTrue(OrderStatus::New->canTransitionTo(OrderStatus::Processing));
        $this->assertTrue(OrderStatus::New->canTransitionTo(OrderStatus::Cancelled));
        $this->assertFalse(OrderStatus::New->canTransitionTo(OrderStatus::Delivered));
        $this->assertFalse(OrderStatus::New->canTransitionTo(OrderStatus::Completed));

        $this->assertTrue(OrderStatus::Processing->canTransitionTo(OrderStatus::Shipped));
        $this->assertTrue(OrderStatus::Processing->canTransitionTo(OrderStatus::Cancelled));
        $this->assertFalse(OrderStatus::Processing->canTransitionTo(OrderStatus::New));

        $this->assertFalse(OrderStatus::Delivered->canTransitionTo(OrderStatus::New));
        $this->assertFalse(OrderStatus::Cancelled->canTransitionTo(OrderStatus::New));
    }

    #[Test]
    public function labels_are_in_russian(): void
    {
        $this->assertSame('Новый', OrderStatus::New->label());
        $this->assertSame('В обработке', OrderStatus::Processing->label());
        $this->assertSame('Отправлен', OrderStatus::Shipped->label());
        $this->assertSame('Доставлен', OrderStatus::Delivered->label());
        $this->assertSame('Завершён', OrderStatus::Completed->label());
        $this->assertSame('Отменён', OrderStatus::Cancelled->label());
    }
}
