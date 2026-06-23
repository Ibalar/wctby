<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Wishlist\Pages;

use App\MoonShine\Resources\Wishlist\WishlistResource;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use Throwable;

/**
 * @extends DetailPage<WishlistResource>
 */
class WishlistDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Пользователь', 'user_id', fn ($item) => $item->user_id ? "User #{$item->user_id}" : 'Гость'),
            Text::make('Товар', 'product_id', fn ($item) => "Product #{$item->product_id}"),
            Text::make('Сессия', 'session_token'),
            Date::make('Добавлено', 'created_at'),
            Date::make('Обновлено', 'updated_at'),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons()->except(['edit']);
    }

    protected function modifyDetailComponent(ComponentContract $component): ComponentContract
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
            ...parent::topLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
