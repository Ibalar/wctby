<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ParsedItem\Pages;

use App\Models\ParsedItem;
use App\MoonShine\Resources\ParsedItem\ParsedItemResource;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/** @extends FormPage<ParsedItemResource> */
class ParsedItemFormPage extends FormPage
{
    /** @return list<ComponentContract|FieldContract> */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('URL', 'source_url')->required(),
                Text::make('Код сайта', 'site_code'),
            ]),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'source_url' => ['required', 'url'],
        ];
    }
}
