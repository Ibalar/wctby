<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Review\Pages;

use App\MoonShine\Resources\Review\ReviewResource;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/** @extends FormPage<ReviewResource> */
class ReviewFormPage extends FormPage
{
    /** @return list<ComponentContract|FieldContract> */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Number::make('Пользователь', 'user_id')->readonly(),
                Number::make('Товар', 'product_id')->readonly(),
                Number::make('Оценка', 'rating')->required()->min(1)->max(5),
                Text::make('Заголовок', 'title'),
                Textarea::make('Содержание', 'body')->required(),
                Switcher::make('Одобрен', 'is_approved')->default(false),
            ]),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body' => ['required', 'string', 'max:5000'],
        ];
    }
}
