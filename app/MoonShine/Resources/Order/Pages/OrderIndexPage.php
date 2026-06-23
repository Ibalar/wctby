<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Order\Pages;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\MoonShine\Resources\Order\OrderResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\DateRange;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use Throwable;

/**
 * @extends IndexPage<OrderResource>
 */
class OrderIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Номер', 'number'),
            Text::make('Статус', 'status')
                ->badge(fn ($value): string => OrderStatus::tryFrom($value)?->color() ?? 'gray'),
            Text::make('Клиент', 'customer_name'),
            Text::make('Телефон', 'customer_phone'),
            Number::make('Сумма', 'total'),
            Date::make('Дата', 'created_at'),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    /**
     * @return list<FieldContract>
     */
    protected function filters(): iterable
    {
        $statusOptions = [];
        foreach (OrderStatus::cases() as $status) {
            $statusOptions[$status->value] = $status->label();
        }

        return [
            Select::make('Статус', 'status')->options($statusOptions),
            DateRange::make('Дата', 'created_at'),
        ];
    }

    /**
     * @return list<QueryTag>
     */
    protected function queryTags(): array
    {
        $tags = [
            QueryTag::make('Все', fn ($query) => $query),
        ];

        foreach (OrderStatus::cases() as $status) {
            $tags[] = QueryTag::make(
                $status->label(),
                fn ($query) => $query->where('status', $status->value),
            );
        }

        return $tags;
    }

    /**
     * @return list<ValueMetric>
     */
    protected function metrics(): array
    {
        return [
            ValueMetric::make('Заказов сегодня')
                ->value(fn (): int => Order::whereDate('created_at', today())->count()),

            ValueMetric::make('Сумма сегодня')
                ->value(fn (): float => (float) Order::whereDate('created_at', today())->sum('total'))
                ->valueFormat(fn (float $value): string => number_format($value, 2) . ' BYN'),
        ];
    }

    protected function modifyListComponent(ComponentContract $component): ComponentContract
    {
        return $component;
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer(),
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer(),
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer(),
        ];
    }
}
