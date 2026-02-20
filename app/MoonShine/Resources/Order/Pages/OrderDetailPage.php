<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Order\Pages;

use App\MoonShine\Resources\OrderItem\OrderItemResource;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use App\MoonShine\Resources\Order\OrderResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;
use Throwable;


/**
 * @extends DetailPage<OrderResource>
 */
class OrderDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Номер', 'number'),
            Text::make('Статус', 'status'),
            Number::make('Subtotal', 'subtotal'),
            Number::make('Скидка', 'discount_amount'),
            Number::make('Доставка', 'shipping_amount'),
            Number::make('Итого', 'total'),
            Text::make('Клиент', 'customer_name'),
            Text::make('Телефон', 'customer_phone'),
            Text::make('Email', 'customer_email'),
            Json::make('Адрес доставки', 'shipping_address'),

            HasMany::make('Позиции заказа', 'items', resource: OrderItemResource::class),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    /**
     * @param  TableBuilder  $component
     *
     * @return TableBuilder
     */
    protected function modifyDetailComponent(ComponentContract $component): ComponentContract
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
            ...parent::topLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
