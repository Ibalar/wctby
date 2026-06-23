<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Review\Pages;

use App\MoonShine\Resources\Review\ReviewResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/** @extends DetailPage<ReviewResource> */
class ReviewDetailPage extends DetailPage
{
    /** @return list<FieldContract> */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Пользователь', 'user.name'),
            Text::make('Товар', 'product.name'),
            Number::make('Оценка', 'rating'),
            Text::make('Заголовок', 'title'),
            Textarea::make('Содержание', 'body'),
            Switcher::make('Одобрен', 'is_approved')->badge(),
        ];
    }
}
