<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Address;

use App\Models\Address;
use App\MoonShine\Resources\Address\Pages\AddressDetailPage;
use App\MoonShine\Resources\Address\Pages\AddressFormPage;
use App\MoonShine\Resources\Address\Pages\AddressIndexPage;
use MoonShine\Laravel\Resources\ModelResource;

class AddressResource extends ModelResource
{
    protected string $model = Address::class;

    protected string $column = 'city';

    public function getTitle(): string
    {
        return 'Адреса';
    }

    protected function pages(): array
    {
        return [
            AddressIndexPage::class,
            AddressFormPage::class,
            AddressDetailPage::class,
        ];
    }
}
