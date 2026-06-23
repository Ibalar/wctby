<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Wishlist\Pages;

use App\Models\Wishlist;
use App\MoonShine\Resources\Wishlist\WishlistResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use Throwable;

/**
 * @extends IndexPage<WishlistResource>
 */
class WishlistIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Пользователь', 'user_id')
                ->formatted(fn ($value) => $value ? "User #{$value}" : 'Гость'),
            Text::make('Товар', 'product_id')
                ->formatted(fn ($value) => "Product #{$value}"),
            Text::make('Сессия', 'session_token')
                ->formatted(fn ($value) => $value ? mb_substr($value, 0, 10) . '...' : '-'),
            Date::make('Добавлено', 'created_at'),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons()->except(['create']);
    }

    /**
     * @return list<FieldContract>
     */
    protected function filters(): iterable
    {
        return [
            Text::make('Пользователь', 'user_id'),
        ];
    }

    /**
     * @return list<QueryTag>
     */
    protected function queryTags(): array
    {
        return [
            QueryTag::make('Все', fn ($query) => $query),
            QueryTag::make('Авторизованные', fn ($query) => $query->whereNotNull('user_id')),
            QueryTag::make('Гостевые', fn ($query) => $query->whereNull('user_id')),
        ];
    }

    /**
     * @return list<ValueMetric>
     */
    protected function metrics(): array
    {
        return [
            ValueMetric::make('Всего в избранном')
                ->value(fn (): int => Wishlist::count()),

            ValueMetric::make('Добавлено сегодня')
                ->value(fn (): int => Wishlist::whereDate('created_at', today())->count()),
        ];
    }

    protected function modifyListComponent(ComponentContract $component): ComponentContract
    {
        return $component;
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer(),
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer(),
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer(),
        ];
    }
}
