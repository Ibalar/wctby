<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\OrderItem\Pages;

use App\MoonShine\Resources\Order\OrderResource;
use App\MoonShine\Resources\OrderItem\OrderItemResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends DetailPage<OrderItemResource>
 */
class OrderItemDetailPage extends DetailPage
{
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
            Number::make('ID товара', 'item_id'),
            Text::make('SKU', 'sku'),
            Number::make('Цена', 'price'),
            Number::make('Количество', 'quantity'),
            Number::make('Сумма', 'line_total'),
            Textarea::make('Meta', 'meta'),
        ];
    }
}
