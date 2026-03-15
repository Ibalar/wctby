<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\User;

use App\Models\User;
use App\MoonShine\Resources\User\Pages\UserDetailPage;
use App\MoonShine\Resources\User\Pages\UserFormPage;
use App\MoonShine\Resources\User\Pages\UserIndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\MenuManager\Attributes\Order;
use MoonShine\Support\Attributes\Icon;

#[Icon('users')]
#[Group('Каталог', 'Покупатели')]
#[Order(1)]
class UserResource extends ModelResource
{
    protected string $model = User::class;

    protected string $column = 'name';

    protected array $with = ['addresses', 'socialAccounts', 'orders'];

    public function getTitle(): string
    {
        return 'Покупатели';
    }

    protected function pages(): array
    {
        return [
            UserIndexPage::class,
            UserFormPage::class,
            UserDetailPage::class,
        ];
    }

    protected function search(): array
    {
        return [
            'name',
            'email',
            'phone',
        ];
    }
}
