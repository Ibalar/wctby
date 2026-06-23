<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Page\Pages;

use App\Models\Page;
use App\MoonShine\Resources\Page\PageResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/** @extends IndexPage<PageResource> */
class PageIndexPage extends IndexPage
{
    /** @return list<FieldContract> */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Заголовок', 'title')->sortable(),
            Text::make('Slug', 'slug'),
            Switcher::make('Активна', 'is_active')->badge(),
        ];
    }

    /** @return list<FieldContract> */
    protected function filters(): iterable
    {
        return [
            Text::make('Заголовок', 'title'),
            Switcher::make('Активна', 'is_active'),
        ];
    }

    /** @return list<QueryTag> */
    protected function queryTags(): array
    {
        return [
            QueryTag::make('Все', fn ($query) => $query),
            QueryTag::make('Активные', fn ($query) => $query->where('is_active', true)),
            QueryTag::make('Неактивные', fn ($query) => $query->where('is_active', false)),
        ];
    }

    /** @return list<ValueMetric> */
    protected function metrics(): array
    {
        return [
            ValueMetric::make('Всего страниц')
                ->value(fn (): int => Page::query()->count()),
            ValueMetric::make('Активных')
                ->value(fn (): int => Page::query()->where('is_active', true)->count()),
        ];
    }
}
