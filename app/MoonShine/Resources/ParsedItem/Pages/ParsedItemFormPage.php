<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ParsedItem\Pages;

use App\Models\ParsedItem;
use App\Models\ParserSite;
use App\MoonShine\Resources\ParsedItem\ParsedItemResource;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/** @extends FormPage<ParsedItemResource> */
class ParsedItemFormPage extends FormPage
{
    /** @return list<ComponentContract|FieldContract> */
    protected function fields(): iterable
    {
        $sites = ParserSite::active()->pluck('name', 'code')->toArray();

        return [
            Box::make([
                ID::make(),
                Select::make('Схема сайта', 'site_code')
                    ->options($sites)
                    ->nullable()
                    ->placeholder('Автоопределение'),
                Text::make('Один URL', 'source_url'),
                Textarea::make('Несколько URL (по одному на строку)', 'urls')
                    ->rows(5)
                    ->placeholder("https://site.by/product/1\nhttps://site.by/product/2"),
            ]),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'source_url' => ['nullable', 'url'],
            'urls' => ['nullable', 'string'],
        ];
    }
}
