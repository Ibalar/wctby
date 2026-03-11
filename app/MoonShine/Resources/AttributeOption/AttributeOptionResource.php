<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\AttributeOption;

use Illuminate\Database\Eloquent\Model;
use App\Models\AttributeOption;
use App\MoonShine\Resources\AttributeOption\Pages\AttributeOptionIndexPage;
use App\MoonShine\Resources\AttributeOption\Pages\AttributeOptionFormPage;
use App\MoonShine\Resources\AttributeOption\Pages\AttributeOptionDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<AttributeOption, AttributeOptionIndexPage, AttributeOptionFormPage, AttributeOptionDetailPage>
 */
class AttributeOptionResource extends ModelResource
{
    protected string $model = AttributeOption::class;

    protected string $title = 'Опции атрибутов';
    protected string $column = 'label';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            AttributeOptionIndexPage::class,
            AttributeOptionFormPage::class,
            AttributeOptionDetailPage::class,
        ];
    }
}
