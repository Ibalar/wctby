<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\SocialAccount;

use App\Models\SocialAccount;
use App\MoonShine\Resources\SocialAccount\Pages\SocialAccountDetailPage;
use App\MoonShine\Resources\SocialAccount\Pages\SocialAccountFormPage;
use App\MoonShine\Resources\SocialAccount\Pages\SocialAccountIndexPage;
use MoonShine\Laravel\Resources\ModelResource;

class SocialAccountResource extends ModelResource
{
    protected string $model = SocialAccount::class;

    protected string $column = 'provider';

    public function getTitle(): string
    {
        return 'Соц. аккаунты';
    }

    protected function pages(): array
    {
        return [
            SocialAccountIndexPage::class,
            SocialAccountFormPage::class,
            SocialAccountDetailPage::class,
        ];
    }
}
