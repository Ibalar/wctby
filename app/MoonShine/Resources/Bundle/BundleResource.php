<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Bundle;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bundle;
use App\MoonShine\Resources\Bundle\Pages\BundleIndexPage;
use App\MoonShine\Resources\Bundle\Pages\BundleFormPage;
use App\MoonShine\Resources\Bundle\Pages\BundleDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Bundle, BundleIndexPage, BundleFormPage, BundleDetailPage>
 */
class BundleResource extends ModelResource
{
    protected string $model = Bundle::class;

    protected string $title = 'Комплекты';

    protected string $column = 'name';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            BundleIndexPage::class,
            BundleFormPage::class,
            BundleDetailPage::class,
        ];
    }
}
