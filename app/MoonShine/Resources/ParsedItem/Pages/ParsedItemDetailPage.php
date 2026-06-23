<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ParsedItem\Pages;

use App\MoonShine\Resources\ParsedItem\ParsedItemResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/** @extends DetailPage<ParsedItemResource> */
class ParsedItemDetailPage extends DetailPage
{
    /** @return list<FieldContract> */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('URL', 'source_url'),
            Text::make('Статус', 'status'),
            Text::make('Сайт', 'site_code'),
            Text::make('Товар', 'product.name', fn ($item) => $item->product?->name ?? '-'),
            Textarea::make('Ошибка', 'error_message'),
            Json::make('Данные', 'raw_data'),
        ];
    }
}
