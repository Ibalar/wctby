<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'provider_token',
        'provider_refresh_token',
        'nickname',
        'avatar',
    ];

    protected $hidden = [
        'provider_token',
        'provider_refresh_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Найти аккаунт по провайдеру и provider_id
     */
    public static function findByProvider(string $provider, string $providerId): ?self
    {
        return static::where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();
    }

    /**
     * Создать или обновить аккаунт
     */
    public static function createOrUpdate(int $userId, string $provider, array $data): self
    {
        return static::updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $data['provider_id'],
            ],
            [
                'user_id' => $userId,
                'nickname' => $data['nickname'] ?? null,
                'avatar' => $data['avatar'] ?? null,
                'provider_token' => $data['token'] ?? null,
                'provider_refresh_token' => $data['refresh_token'] ?? null,
            ]
        );
    }
}
