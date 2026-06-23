<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ParserSite\Pages;

use App\MoonShine\Resources\ParserSite\ParserSiteResource;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/** @extends FormPage<ParserSiteResource> */
class ParserSiteFormPage extends FormPage
{
    /** @return list<ComponentContract|FieldContract> */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Название', 'name')->required(),
                Text::make('Код', 'code')->required(),
                Json::make('Домены', 'domains')
                    ->removable()
                    ->keyValue('Домен', 'Значение'),
                Switcher::make('Активна', 'is_active')->default(true),
            ]),
            Box::make('Селекторы', [
                Json::make('Название (name)', 'selectors->name')
                    ->keyValue('Приоритет', 'Селектор'),
                Json::make('Цена (price)', 'selectors->price')
                    ->keyValue('Приоритет', 'Селектор'),
                Json::make('Описание (description)', 'selectors->description')
                    ->keyValue('Приоритет', 'Селектор'),
                Json::make('Изображение (image)', 'selectors->image')
                    ->keyValue('Приоритет', 'Селектор'),
            ]),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:parser_sites,code,' . $item->getKey()],
        ];
    }
}
