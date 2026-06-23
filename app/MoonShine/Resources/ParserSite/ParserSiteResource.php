<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ParserSite;

use App\Models\ParserSite;
use App\MoonShine\Resources\ParserSite\Pages\ParserSiteIndexPage;
use App\MoonShine\Resources\ParserSite\Pages\ParserSiteFormPage;
use App\MoonShine\Resources\ParserSite\Pages\ParserSiteDetailPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

class ParserSiteResource extends ModelResource
{
    protected string $model = ParserSite::class;
    protected string $title = 'Схемы парсинга';
    protected string $column = 'name';

    /** @return list<class-string<PageContract>> */
    protected function pages(): array
    {
        return [
            ParserSiteIndexPage::class,
            ParserSiteFormPage::class,
            ParserSiteDetailPage::class,
        ];
    }
}
