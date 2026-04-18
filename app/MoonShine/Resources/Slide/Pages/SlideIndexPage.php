<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Slide\Pages;

use App\Models\Slide;
use App\MoonShine\Resources\Slide\SlideResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<SlideResource>
 */
class SlideIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Image::make('Изображение', 'image')
                ->removable(false),
            Text::make('Заголовок', 'title')->sortable(),
            Text::make('Ссылка', 'link'),
            Text::make('Текст кнопки', 'link_text'),
            Number::make('Позиция', 'position')->sortable(),
            Switcher::make('Активен', 'active')->badge(),
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function filters(): iterable
    {
        return [
            Text::make('Заголовок', 'title'),
            Text::make('Ссылка', 'link'),
            Switcher::make('Активен', 'active'),
        ];
    }

    /**
     * @return list<QueryTag>
     */
    protected function queryTags(): array
    {
        return [
            QueryTag::make('Все', fn ($query) => $query),
            QueryTag::make('Активные', fn ($query) => $query->where('active', true)),
            QueryTag::make('Неактивные', fn ($query) => $query->where('active', false)),
        ];
    }

    /**
     * @return list<ValueMetric>
     */
    protected function metrics(): array
    {
        return [
            ValueMetric::make('Всего слайдов')
                ->value(fn (): int => Slide::query()->count()),
            ValueMetric::make('Активных')
                ->value(fn (): int => Slide::query()->where('active', true)->count()),
        ];
    }
}
