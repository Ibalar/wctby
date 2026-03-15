<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Address\Pages;

use App\MoonShine\Resources\Address\AddressResource;
use App\MoonShine\Resources\User\UserResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

class AddressFormPage extends FormPage
{
    public function resource(): string
    {
        return AddressResource::class;
    }

    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                BelongsTo::make('Пользователь', 'user', resource: UserResource::class)
                    ->required(),
                Select::make('Тип', 'type')
                    ->options(['shipping' => 'Доставка', 'billing' => 'Счета'])
                    ->default('shipping')
                    ->required(),
                Text::make('Город', 'city')->required(),
                Text::make('Улица', 'street')->required(),
                Text::make('Дом', 'house'),
                Text::make('Квартира', 'apartment'),
                Text::make('Индекс', 'postal_code'),
                Switcher::make('Основной', 'is_default'),
            ]),
        ];
    }
}
