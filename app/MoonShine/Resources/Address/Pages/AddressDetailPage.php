<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Address\Pages;

use App\MoonShine\Resources\Address\AddressResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

class AddressDetailPage extends DetailPage
{
    public function resource(): string
    {
        return AddressResource::class;
    }

    protected function fields(): iterable
    {
        return [
            ID::make(),
            BelongsTo::make('Пользователь', 'user')->readonly(),
            Select::make('Тип', 'type')
                ->options(['shipping' => 'Доставка', 'billing' => 'Счета'])
                ->readonly(),
            Text::make('Город', 'city')->readonly(),
            Text::make('Улица', 'street')->readonly(),
            Text::make('Дом', 'house')->readonly(),
            Text::make('Квартира', 'apartment')->readonly(),
            Text::make('Индекс', 'postal_code')->readonly(),
            Switcher::make('Основной', 'is_default')->readonly(),
        ];
    }
}
