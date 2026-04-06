<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\DeliveryMethod\Pages;

use App\MoonShine\Resources\DeliveryMethod\DeliveryMethodResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<DeliveryMethodResource>
 */
class DeliveryMethodIndexPage extends IndexPage
{
    /**
     * @return iterable<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Название', 'name'),
            Text::make('Код', 'code'),
            Number::make('Цена', 'price'),
            Switcher::make('Активен', 'is_active')->badge(),
            Number::make('Сортировка', 'sort_order'),
        ];
    }

    /**
     * @return iterable<FieldContract>
     */
    protected function filters(): iterable
    {
        return [
            Text::make('Название', 'name'),
            Text::make('Код', 'code'),
            Switcher::make('Активен', 'is_active'),
        ];
    }
}
