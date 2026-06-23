<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ParsedItem\Pages;

use App\Jobs\ParseProductJob;
use App\Models\ParsedItem;
use App\MoonShine\Resources\ParsedItem\ParsedItemResource;
use Illuminate\Support\Facades\Log;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

/** @extends IndexPage<ParsedItemResource> */
class ParsedItemIndexPage extends IndexPage
{
    /** @return list<FieldContract> */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('URL', 'source_url', fn ($item) => mb_substr($item->source_url, 0, 60) . (mb_strlen($item->source_url) > 60 ? '...' : '')),
            Text::make('Статус', 'status')->badge(fn ($value): string => match ($value) {
                'pending' => 'gray',
                'processing' => 'yellow',
                'done' => 'green',
                'failed' => 'red',
                default => 'gray',
            }),
            Text::make('Товар', 'product.name', fn ($item) => $item->product?->name ?? '-'),
            Date::make('Дата', 'created_at')->sortable(),
        ];
    }

    /** @return list<QueryTag> */
    protected function queryTags(): array
    {
        return [
            QueryTag::make('Все', fn ($query) => $query),
            QueryTag::make('В очереди', fn ($query) => $query->where('status', 'pending')),
            QueryTag::make('Готово', fn ($query) => $query->where('status', 'done')),
            QueryTag::make('Ошибки', fn ($query) => $query->where('status', 'failed')),
        ];
    }

    /** @return list<ValueMetric> */
    protected function metrics(): array
    {
        return [
            ValueMetric::make('Всего URL')
                ->value(fn (): int => ParsedItem::count()),
            ValueMetric::make('В очереди')
                ->value(fn (): int => ParsedItem::pending()->count()),
            ValueMetric::make('Готово')
                ->value(fn (): int => ParsedItem::where('status', 'done')->count()),
        ];
    }

    /** @return list<ComponentContract> */
    protected function topLayer(): array
    {
        return [
            ActionButton::make('Запустить парсинг', route('moonshine.parser.run'))
                ->primary(),
            ...parent::topLayer(),
        ];
    }

    protected function lineButtons(): ListOf
    {
        return ListOf::make([
            ActionButton::make('Перезапустить', fn ($item) => route('moonshine.parser.reparse', ['id' => $item->getKey()]))
                ->warning()
                ->icon('arrow-path'),
        ]);
    }
}
