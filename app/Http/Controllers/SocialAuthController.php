<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    protected array $providers = ['google', 'vkontakte', 'telegram', 'yandex'];

    public function redirect(string $provider)
    {
        if (! in_array($provider, $this->providers)) {
            return redirect()->route('login')->with('error', 'Неподдерживаемый провайдер авторизации');
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Ошибка перенаправления к провайдеру');
        }
    }

    public function callback(string $provider)
    {
        if (! in_array($provider, $this->providers)) {
            return redirect()->route('login')->with('error', 'Неподдерживаемый провайдер авторизации');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Ошибка авторизации через '.ucfirst($provider));
        }

        $socialAccount = SocialAccount::findByProvider($provider, $socialUser->getId());

        if ($socialAccount) {
            Auth::login($socialAccount->user, true);

            return redirect()->intended(route('profile.index'));
        }

        if (Auth::check()) {
            $this->linkAccount(Auth::user(), $provider, $socialUser);

            return redirect()->route('profile.social')->with('success', 'Аккаунт '.ucfirst($provider).' успешно привязан');
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            $this->linkAccount($user, $provider, $socialUser);
            Auth::login($user, true);

            return redirect()->intended(route('profile.index'));
        }

        $user = $this->createUser($provider, $socialUser);
        $this->linkAccount($user, $provider, $socialUser);

        Auth::login($user, true);

        return redirect()->route('profile.edit')->with('success', 'Аккаунт создан через '.ucfirst($provider).'. Пожалуйста, завершите регистрацию.');
    }

    protected function linkAccount(User $user, string $provider, $socialUser): SocialAccount
    {
        return SocialAccount::createOrUpdate($user->id, $provider, [
            'provider_id' => $socialUser->getId(),
            'nickname' => $socialUser->getNickname() ?? $socialUser->getName(),
            'avatar' => $socialUser->getAvatar(),
            'token' => $socialUser->token,
            'refresh_token' => $socialUser->refreshToken,
        ]);
    }

    protected function createUser(string $provider, $socialUser): User
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
            'email_verified_at' => $socialUser->getEmail() ? now() : null,
            'password' => bcrypt(Str::random(32)),
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

    public function unlink(string $provider)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (! $user->password) {
            return back()->with('error', 'Невозможно отвязать последний способ входа. Сначала задайте пароль.');
        }

        if ($user->socialAccounts()->count() === 1 && ! $user->password) {
            return back()->with('error', 'Невозможно отвязать последний способ входа.');
        }

        $deleted = $user->socialAccounts()->where('provider', $provider)->delete();

        if ($deleted) {
            return back()->with('success', 'Аккаунт '.ucfirst($provider).' отвязан');
        }

        return back()->with('error', 'Аккаунт не найден');
    }
}
