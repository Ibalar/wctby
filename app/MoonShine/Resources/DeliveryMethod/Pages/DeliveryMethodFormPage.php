<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\DeliveryMethod\Pages;

use App\MoonShine\Resources\DeliveryMethod\DeliveryMethodResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends FormPage<DeliveryMethodResource>
 */
class DeliveryMethodFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Название', 'name')->required(),
                Text::make('Код', 'code')->required(),
                Textarea::make('Описание', 'description'),
                Number::make('Цена', 'price')->min(0)->step(0.01),
                Switcher::make('Активен', 'is_active'),
                Number::make('Порядок', 'sort_order'),
            ]),
        ];
    }
}
