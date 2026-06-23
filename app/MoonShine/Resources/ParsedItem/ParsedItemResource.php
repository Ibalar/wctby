<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ParsedItem;

use App\Models\ParsedItem;
use App\MoonShine\Resources\ParsedItem\Pages\ParsedItemIndexPage;
use App\MoonShine\Resources\ParsedItem\Pages\ParsedItemDetailPage;
use App\MoonShine\Resources\ParsedItem\Pages\ParsedItemFormPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<ParsedItem, ParsedItemIndexPage, ParsedItemFormPage, ParsedItemDetailPage>
 */
class ParsedItemResource extends ModelResource
{
    protected string $model = ParsedItem::class;

    protected string $title = 'Парсинг товаров';

    protected string $column = 'source_url';

    protected string $sortColumn = 'created_at';

    protected bool $createInModal = false;

    /** @return list<class-string<PageContract>> */
    protected function pages(): array
    {
        return [
            ParsedItemIndexPage::class,
            ParsedItemFormPage::class,
            ParsedItemDetailPage::class,
        ];
    }
}
