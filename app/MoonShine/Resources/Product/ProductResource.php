<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\MoonShine\Resources\Product\Pages\ProductIndexPage;
use App\MoonShine\Resources\Product\Pages\ProductFormPage;
use App\MoonShine\Resources\Product\Pages\ProductDetailPage;

use MoonShine\ImportExport\Contracts\HasImportExportContract;
use MoonShine\ImportExport\ExportHandler;
use MoonShine\ImportExport\ImportHandler;
use MoonShine\ImportExport\Traits\ImportExportConcern;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Crud\Handlers\Handler;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends ModelResource<Product, ProductIndexPage, ProductFormPage, ProductDetailPage>
 */
class ProductResource extends ModelResource implements HasImportExportContract
{
    use ImportExportConcern;

    protected string $model = Product::class;

    protected string $title = 'Товары';

    protected string $column = 'name';

    /** @return list<class-string<PageContract>> */
    protected function pages(): array
    {
        return [
            ProductIndexPage::class,
            ProductFormPage::class,
            ProductDetailPage::class,
        ];
    }

    protected function importFields(): iterable
    {
        return [
            ID::make(),
            Text::make('SKU / Артикул', 'sku'),
            Text::make('Название', 'name'),
            Text::make('Slug', 'slug'),
            Number::make('Базовая цена', 'base_price'),
            Textarea::make('Описание', 'description'),
            Switcher::make('Активен', 'is_active'),
            Text::make('Meta Title', 'meta_title'),
            Text::make('Meta Description', 'meta_description'),
        ];
    }

    protected function import(): ?Handler
    {
        return ImportHandler::make('Импорт')
            ->delimiter(',')
            ->queue();
    }

    protected function exportFields(): iterable
    {
        return [
            ID::make(),
            Text::make('SKU', 'sku'),
            Text::make('Название', 'name'),
            Text::make('Slug', 'slug'),
            Number::make('Базовая цена', 'base_price'),
            Text::make('Категория', 'category_id'),
            Switcher::make('Активен', 'is_active'),
        ];
    }

    protected function export(): ?Handler
    {
        return ExportHandler::make('Экспорт')
            ->csv()
            ->delimiter(',')
            ->filename(sprintf('products_%s', date('Ymd-His')));
    }
}
