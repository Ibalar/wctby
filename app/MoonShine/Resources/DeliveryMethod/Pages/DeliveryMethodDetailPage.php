<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\DeliveryMethod\Pages;

use App\MoonShine\Resources\DeliveryMethod\DeliveryMethodResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends DetailPage<DeliveryMethodResource>
 */
class DeliveryMethodDetailPage extends DetailPage
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
            Textarea::make('Описание', 'description'),
            Number::make('Цена', 'price'),
            Switcher::make('Активен', 'is_active'),
            Number::make('Порядок', 'sort_order'),
        ];
    }
}
