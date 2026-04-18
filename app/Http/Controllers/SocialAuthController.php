<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SocialAccountService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function __construct(
        protected SocialAccountService $socialAccounts
    ) {}

    public function redirect(string $provider)
    {
        if (! $this->socialAccounts->isSupportedProvider($provider)) {
            return redirect()->route('login')->with('error', 'Неподдерживаемый провайдер авторизации');
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception) {
            return redirect()->route('login')->with('error', 'Ошибка перенаправления к провайдеру');
        }
    }

    public function callback(string $provider)
    {
        if (! $this->socialAccounts->isSupportedProvider($provider)) {
            return redirect()->route('login')->with('error', 'Неподдерживаемый провайдер авторизации');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception) {
            return redirect()->route('login')->with('error', 'Ошибка авторизации через '.ucfirst($provider));
        }

        $providerId = (string) $socialUser->getId();
        $socialAccount = $this->socialAccounts->findByProviderAndId($provider, $providerId);

        if ($socialAccount) {
            if (Auth::check() && Auth::id() !== $socialAccount->user_id) {
                return redirect()->route('profile.social')->with(
                    'error',
                    'Этот социальный аккаунт уже привязан к другому пользователю.'
                );
            }

            if (Auth::check() && Auth::id() === $socialAccount->user_id) {
                return redirect()->route('profile.social')->with(
                    'success',
                    'Аккаунт '.ucfirst($provider).' уже привязан.'
                );
            }

            Auth::login($socialAccount->user, true);

            return redirect()->intended(route('profile.index'));
        }

        if (Auth::check()) {
            try {
                $this->socialAccounts->linkAccount(Auth::user(), $provider, $socialUser);
            } catch (\DomainException $e) {
                return redirect()->route('profile.social')->with('error', $e->getMessage());
            }

            return redirect()->route('profile.social')->with('success', 'Аккаунт '.ucfirst($provider).' успешно привязан');
        }

        $email = $socialUser->getEmail();
        $emailVerifiedByProvider = $this->isProviderEmailVerified($provider, $socialUser);
        $user = $email ? User::where('email', $email)->first() : null;

        if ($user) {
            if (! $emailVerifiedByProvider) {
                return redirect()->route('login')->with(
                    'error',
                    'Провайдер не подтвердил email. Войдите в аккаунт паролем и привяжите соцсеть в личном кабинете.'
                );
            }

            try {
                $this->socialAccounts->linkAccount($user, $provider, $socialUser);
            } catch (\DomainException $e) {
                return redirect()->route('login')->with('error', $e->getMessage());
            }
            Auth::login($user, true);

            return redirect()->intended(route('profile.index'));
        }

        $user = $this->createUser($provider, $socialUser);

        try {
            $this->socialAccounts->linkAccount($user, $provider, $socialUser);
        } catch (\DomainException $e) {
            return redirect()->route('login')->with('error', $e->getMessage());
        }

        Auth::login($user, true);

        return redirect()->route('profile.edit')->with('success', 'Аккаунт создан через '.ucfirst($provider).'. Пожалуйста, завершите регистрацию.');
    }

    protected function createUser(string $provider, mixed $socialUser): User
    {
        $name = $socialUser->getName() ?? $socialUser->getNickname() ?? 'User';

        $firstName = null;
        $lastName = null;

        if ($name && $name !== 'User') {
            $parts = explode(' ', $name, 2);
            $firstName = $parts[0] ?? null;
            $lastName = $parts[1] ?? null;
        }

        return User::create([
            'name' => $name,
            'email' => $socialUser->getEmail() ?? $this->generateFakeEmail($provider, $socialUser->getId()),
            'email_verified_at' => $socialUser->getEmail() && $this->isProviderEmailVerified($provider, $socialUser)
                ? now()
                : null,
            'password' => Hash::make(Str::random(32)),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'avatar' => $this->downloadAvatar($socialUser->getAvatar()),
        ]);
    }

    protected function generateFakeEmail(string $provider, string $providerId): string
    {
        return strtolower($provider).'_'.$providerId.'@example.com';
    }

    protected function downloadAvatar(?string $url): ?string
    {
        return null;
    }

    protected function isProviderEmailVerified(string $provider, mixed $socialUser): bool
    {
        $raw = method_exists($socialUser, 'user') && is_array($socialUser->user)
            ? $socialUser->user
            : [];

        if ($provider === 'google') {
            return $this->toBoolean($raw['email_verified'] ?? null);
        }

        if ($provider === 'yandex') {
            return $this->toBoolean($raw['default_email'] ?? null)
                || $this->toBoolean($raw['is_email_verified'] ?? null);
        }

        return false;
    }

    protected function toBoolean(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            return (int) $value === 1;
        }

        if (is_string($value)) {
            return in_array(strtolower($value), ['1', 'true', 'yes'], true);
        }

        return false;
    }

    public function unlink(string $provider)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (! $this->socialAccounts->isSupportedProvider($provider)) {
            return back()->with('error', 'Неподдерживаемый провайдер авторизации');
        }

        $result = $this->socialAccounts->unlinkAccount(Auth::user(), $provider);

        if ($result === SocialAccountService::UNLINK_NOT_FOUND) {
            return back()->with('error', 'Аккаунт не найден');
        }

        if ($result === SocialAccountService::UNLINK_LAST_METHOD) {
            return back()->with('error', 'Невозможно отвязать последний способ входа.');
        }

        return back()->with('success', 'Аккаунт '.ucfirst($provider).' отвязан');
    }
}
