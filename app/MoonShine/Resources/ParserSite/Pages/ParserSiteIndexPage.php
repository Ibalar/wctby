<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ParserSite\Pages;

use App\MoonShine\Resources\ParserSite\ParserSiteResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/** @extends IndexPage<ParserSiteResource> */
class ParserSiteIndexPage extends IndexPage
{
    /** @return list<FieldContract> */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'name')->sortable(),
            Text::make('Код', 'code')->sortable(),
            Text::make('Доменов', 'domains', fn ($item) => count($item->domains) . ' шт.'),
            Switcher::make('Активна', 'is_active'),
        ];
    }
}
