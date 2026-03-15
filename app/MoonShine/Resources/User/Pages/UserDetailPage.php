<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\User\Pages;

use App\MoonShine\Resources\User\UserResource;
use MoonShine\Laravel\Pages\Crud\DetailPage;

class UserDetailPage extends DetailPage
{
    public function resource(): string
    {
        return UserResource::class;
    }

    protected function fields(): iterable
    {
        return (new UserFormPage())->fields();
    }
}
