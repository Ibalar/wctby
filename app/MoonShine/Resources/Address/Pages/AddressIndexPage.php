<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Address\Pages;

use App\MoonShine\Resources\Address\AddressResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

class AddressIndexPage extends IndexPage
{
    public function resource(): string
    {
        return AddressResource::class;
    }

    protected function fields(): iterable
    {
        return [
            ID::make(),
            BelongsTo::make('Пользователь', 'user')->link(),
            Text::make('Город', 'city'),
            Text::make('Улица', 'street'),
            Switcher::make('Основной', 'is_default')->readonly(),
        ];
    }
}
