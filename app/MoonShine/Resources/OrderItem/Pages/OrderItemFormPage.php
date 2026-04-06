<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\OrderItem\Pages;

use App\MoonShine\Resources\Order\OrderResource;
use App\MoonShine\Resources\OrderItem\OrderItemResource;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends FormPage<OrderItemResource>
 */
class OrderItemFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                BelongsTo::make('Заказ', 'order', resource: OrderResource::class)
                    ->searchable()
                    ->required(),
                Text::make('Название', 'name')->required(),
                Text::make('Тип позиции', 'item_type')->required(),
                Number::make('ID товара', 'item_id'),
                Text::make('SKU', 'sku'),
                Number::make('Цена', 'price')->min(0)->step(0.01)->required(),
                Number::make('Количество', 'quantity')->min(1)->required(),
                Number::make('Сумма', 'line_total')->min(0)->step(0.01)->required(),
                Textarea::make('Meta', 'meta')
                    ->hint('Дополнительные данные позиции в JSON или текстовом виде'),
            ]),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'item_type' => ['required', 'string', 'max:255'],
            'item_id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:1'],
            'line_total' => ['required', 'numeric', 'min:0'],
            'meta' => ['nullable'],
        ];
    }
}
