<?php

namespace App\Services;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class SocialAccountService
{
    public const UNLINK_SUCCESS = 'success';
    public const UNLINK_NOT_FOUND = 'not_found';
    public const UNLINK_LAST_METHOD = 'last_method';

    /**
     * @return list<string>
     */
    public function providers(): array
    {
        $providers = array_keys(config('social_auth.providers', []));

        return array_values(array_filter($providers, static fn ($provider) => is_string($provider) && $provider !== ''));
    }

    public function isSupportedProvider(string $provider): bool
    {
        return in_array($provider, $this->providers(), true);
    }

    public function findByProviderAndId(string $provider, string $providerId): ?SocialAccount
    {
        return SocialAccount::findByProvider($provider, $providerId);
    }

    /**
     * @throws \DomainException
     */
    public function linkAccount(User $user, string $provider, mixed $socialUser): SocialAccount
    {
        $providerId = (string) $socialUser->getId();

        try {
            return DB::transaction(function () use ($user, $provider, $providerId, $socialUser): SocialAccount {
                $accountByProviderId = SocialAccount::query()
                    ->where('provider', $provider)
                    ->where('provider_id', $providerId)
                    ->lockForUpdate()
                    ->first();

                if ($accountByProviderId && (int) $accountByProviderId->user_id !== (int) $user->id) {
                    throw new \DomainException('Этот социальный аккаунт уже привязан к другому пользователю.');
                }

                $accountByUserProvider = SocialAccount::query()
                    ->where('user_id', $user->id)
                    ->where('provider', $provider)
                    ->lockForUpdate()
                    ->first();

                $account = $accountByUserProvider ?? $accountByProviderId ?? new SocialAccount([
                    'user_id' => $user->id,
                    'provider' => $provider,
                ]);

                $account->fill([
                    'provider_id' => $providerId,
                    'nickname' => $socialUser->getNickname() ?? $socialUser->getName(),
                    'avatar' => $socialUser->getAvatar(),
                    'provider_token' => $socialUser->token ?? null,
                    'provider_refresh_token' => $socialUser->refreshToken ?? null,
                ]);
                $account->user_id = $user->id;
                $account->provider = $provider;
                $account->save();

                return $account;
            }, 3);
        } catch (QueryException $e) {
            if ($this->isUniqueConstraintViolation($e)) {
                throw new \DomainException('Не удалось привязать соцаккаунт: он уже связан с другим профилем.', 0, $e);
            }

            throw $e;
        }
    }

    /**
     * @return self::UNLINK_SUCCESS|self::UNLINK_NOT_FOUND|self::UNLINK_LAST_METHOD
     */
    public function unlinkAccount(User $user, string $provider): string
    {
        $accountQuery = $user->socialAccounts()->where('provider', $provider);

        if (! $accountQuery->exists()) {
            return self::UNLINK_NOT_FOUND;
        }

        $hasPassword = filled($user->password);
        $socialAccountsCount = $user->socialAccounts()->count();

        if (! $hasPassword && $socialAccountsCount <= 1) {
            return self::UNLINK_LAST_METHOD;
        }

        return $accountQuery->delete() ? self::UNLINK_SUCCESS : self::UNLINK_NOT_FOUND;
    }

    protected function isUniqueConstraintViolation(QueryException $e): bool
    {
        $sqlState = $e->errorInfo[0] ?? null;

        if (in_array($sqlState, ['23000', '23505'], true)) {
            return true;
        }

        $message = strtolower($e->getMessage());

        return str_contains($message, 'duplicate')
            || str_contains($message, 'unique constraint')
            || str_contains($message, 'unique failed');
    }
}
