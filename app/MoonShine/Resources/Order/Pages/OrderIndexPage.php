<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Order\Pages;

use App\Models\Order;
use App\MoonShine\Resources\Order\OrderResource;
use Carbon\Carbon;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Metrics\Wrapped\Metric;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Badge;
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
            Text::make('Номер', 'number')->link(),
            Badge::make('Статус', 'status')->colors([
                'new' => 'primary',
                'processing' => 'warning',
                'completed' => 'success',
            ]),
            Text::make('Клиент', 'customer_name'),
            Text::make('Телефон', 'customer_phone'),
            Number::make('Сумма', 'total')->money(),
            Date::make('Дата', 'created_at')->format('d.m.Y H:i'),
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
        return [
            Select::make('Статус', 'status')->options([
                'new' => 'Новый',
                'processing' => 'В обработке',
                'completed' => 'Выполнен',
            ]),
            DateRange::make('Дата', 'created_at'),
        ];
    }

    /**
     * @return list<QueryTag>
     */
    protected function queryTags(): array
    {
        return [
            QueryTag::make('Все'),
            QueryTag::make('Новые', fn ($query) => $query->where('status', 'new')),
            QueryTag::make('В обработке', fn ($query) => $query->where('status', 'processing')),
            QueryTag::make('Выполненные', fn ($query) => $query->where('status', 'completed')),
        ];
    }

    /**
     * @return list<Metric>
     */
    protected function metrics(): array
    {
        $today = Carbon::today();

        return [
            Metric::make('Заказов', (string) Order::query()->whereDate('created_at', $today)->count()),
            Metric::make('Сумма', (string) Order::query()->whereDate('created_at', $today)->sum('total')),
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
