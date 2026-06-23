<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Bundle\Pages;

use App\MoonShine\Resources\BundleItem\BundleItemResource;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\FieldContract;
use App\MoonShine\Resources\Bundle\BundleResource;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/** @extends DetailPage<BundleResource> */
class BundleDetailPage extends DetailPage
{
    /** @return list<FieldContract> */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Название', 'name'),
            Text::make('Slug', 'slug'),
            Number::make('Цена', 'total_price'),
            Textarea::make('Описание', 'description'),
            Switcher::make('Активен', 'is_active'),
            HasMany::make('Товары в комплекте', 'items', resource: BundleItemResource::class),
        ];
    }
}
