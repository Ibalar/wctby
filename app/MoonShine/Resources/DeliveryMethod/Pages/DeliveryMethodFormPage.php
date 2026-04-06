<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\DeliveryMethod\Pages;

use App\MoonShine\Resources\DeliveryMethod\DeliveryMethodResource;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
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
                Text::make('Код', 'code')
                    ->required()
                    ->hint('Уникальный системный код, например courier или pickup'),
                Textarea::make('Описание', 'description'),
                Number::make('Цена', 'price')
                    ->min(0)
                    ->step(0.01)
                    ->default(0),
                Switcher::make('Активен', 'is_active')->default(true),
                Number::make('Порядок сортировки', 'sort_order')->default(0),
            ]),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        $methodId = $item->getKey();

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:delivery_methods,code,' . $methodId],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ];
    }
}
