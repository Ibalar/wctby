<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Review\Pages;

use App\Models\Review;
use App\MoonShine\Resources\Review\ReviewResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/** @extends IndexPage<ReviewResource> */
class ReviewIndexPage extends IndexPage
{
    /** @return list<FieldContract> */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Пользователь', 'user.name'),
            Text::make('Товар', 'product.name'),
            Text::make('Оценка', 'rating')
                ->badge(fn ($value): string => match ((int) $value) {
                    5 => 'green',
                    4 => 'blue',
                    3 => 'yellow',
                    2 => 'orange',
                    1 => 'red',
                    default => 'gray',
                }),
            Text::make('Отзыв', 'body')->formatted(fn ($value): string => mb_substr(strip_tags($value), 0, 100) . (mb_strlen(strip_tags($value)) > 100 ? '...' : '')),
            Switcher::make('Одобрен', 'is_approved')->badge(),
            Date::make('Дата', 'created_at')->sortable(),
        ];
    }

    /** @return list<FieldContract> */
    protected function filters(): iterable
    {
        return [
            Switcher::make('Одобрен', 'is_approved'),
        ];
    }

    /** @return list<QueryTag> */
    protected function queryTags(): array
    {
        return [
            QueryTag::make('Все', fn ($query) => $query),
            QueryTag::make('Одобренные', fn ($query) => $query->where('is_approved', true)),
            QueryTag::make('На модерации', fn ($query) => $query->where('is_approved', false)),
        ];
    }

    /** @return list<ValueMetric> */
    protected function metrics(): array
    {
        return [
            ValueMetric::make('Всего отзывов')
                ->value(fn (): int => Review::query()->count()),
            ValueMetric::make('На модерации')
                ->value(fn (): int => Review::query()->where('is_approved', false)->count()),
        ];
    }
}
