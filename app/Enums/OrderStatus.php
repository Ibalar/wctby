<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
    case New = 'new';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Новый',
            self::Processing => 'В обработке',
            self::Shipped => 'Отправлен',
            self::Delivered => 'Доставлен',
            self::Completed => 'Завершён',
            self::Cancelled => 'Отменён',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::New => 'blue',
            self::Processing => 'yellow',
            self::Shipped => 'purple',
            self::Delivered => 'green',
            self::Completed => 'gray',
            self::Cancelled => 'red',
        };
    }

    /**
     * @return list<OrderStatus>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::New => [self::Processing, self::Cancelled],
            self::Processing => [self::Shipped, self::Cancelled],
            self::Shipped => [self::Delivered],
            self::Delivered => [self::Completed],
            self::Completed => [],
            self::Cancelled => [],
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedTransitions(), true);
    }
}
