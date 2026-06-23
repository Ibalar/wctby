<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ParserSite\Pages;

use App\MoonShine\Resources\ParserSite\ParserSiteResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/** @extends DetailPage<ParserSiteResource> */
class ParserSiteDetailPage extends DetailPage
{
    /** @return list<FieldContract> */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Название', 'name'),
            Text::make('Код', 'code'),
            Json::make('Домены', 'domains'),
            Json::make('Селекторы', 'selectors'),
            Switcher::make('Активна', 'is_active'),
        ];
    }
}
