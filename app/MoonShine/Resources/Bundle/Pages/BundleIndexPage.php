<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Bundle\Pages;

use App\Models\Bundle;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use App\MoonShine\Resources\Bundle\BundleResource;

/** @extends IndexPage<BundleResource> */
class BundleIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /** @return list<FieldContract> */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'name')->sortable(),
            Text::make('Slug', 'slug'),
            Number::make('Цена', 'total_price')->sortable(),
            Text::make('Товаров', 'items_count', fn ($item) => $item->items()->count()),
            Switcher::make('Активен', 'is_active'),
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
            ValueMetric::make('Всего комплектов')
                ->value(fn (): int => Bundle::query()->count()),
            ValueMetric::make('Активных')
                ->value(fn (): int => Bundle::query()->where('is_active', true)->count()),
        ];
    }
}
