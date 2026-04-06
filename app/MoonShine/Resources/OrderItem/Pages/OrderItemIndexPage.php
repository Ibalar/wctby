<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\OrderItem\Pages;

use App\Models\OrderItem;
use App\MoonShine\Resources\Order\OrderResource;
use App\MoonShine\Resources\OrderItem\OrderItemResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<OrderItemResource>
 */
class OrderItemIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return iterable<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            BelongsTo::make('Заказ', 'order', resource: OrderResource::class),
            Text::make('Название', 'name'),
            Text::make('Тип позиции', 'item_type'),
            Text::make('SKU', 'sku'),
            Number::make('Цена', 'price'),
            Number::make('Количество', 'quantity'),
            Number::make('Сумма', 'line_total'),
        ];
    }

    /**
     * @return iterable<FieldContract>
     */
    protected function filters(): iterable
    {
        return [
            Text::make('Название', 'name'),
            Text::make('Тип позиции', 'item_type'),
            Text::make('SKU', 'sku'),
        ];
    }

    /**
     * @return list<QueryTag>
     */
    protected function queryTags(): array
    {
        return [
            QueryTag::make('Все', fn ($query) => $query),
            QueryTag::make('SKU', fn ($query) => $query->where('item_type', 'like', '%Sku%')),
            QueryTag::make('Товары', fn ($query) => $query->where('item_type', 'like', '%Product%')),
        ];
    }
}
