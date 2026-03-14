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
            Text::make('Название', 'name')->link(),
            Text::make('Код', 'code'),
            Number::make('Цена', 'price'),
            Switcher::make('Активен', 'is_active')->badge(),
            Text::make('Порядок', 'sort_order'),
        ];
    }

    /**
     * @return iterable<FieldContract>
     */
    protected function filters(): iterable
    {
        return [
            Switcher::make('Активен', 'is_active'),
        ];
    }
}
