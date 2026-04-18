<?php

namespace Tests\Feature;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class SocialAuthCallbackConflictsTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_cannot_link_social_account_of_another_user(): void
    {
        $owner = User::factory()->create();
        $actor = User::factory()->create();

        SocialAccount::query()->create([
            'user_id' => $owner->id,
            'provider' => 'google',
            'provider_id' => 'google-owner-1',
        ]);

        $socialUser = $this->mockSocialUser(
            id: 'google-owner-1',
            email: 'owner@example.test',
            raw: ['email_verified' => true],
        );

        Socialite::shouldReceive('driver')->once()->with('google')->andReturnSelf();
        Socialite::shouldReceive('user')->once()->andReturn($socialUser);

        $response = $this
            ->actingAs($actor)
            ->get(route('social.callback', ['provider' => 'google']));

        $response->assertRedirect(route('profile.social'));
        $response->assertSessionHas('error', 'Этот социальный аккаунт уже привязан к другому пользователю.');
    }

    public function test_guest_cannot_auto_link_existing_email_when_provider_email_not_verified(): void
    {
        $existingUser = User::factory()->create([
            'email' => 'existing@example.test',
        ]);

        $socialUser = $this->mockSocialUser(
            id: 'google-new-1',
            email: $existingUser->email,
            raw: ['email_verified' => false],
        );

        Socialite::shouldReceive('driver')->once()->with('google')->andReturnSelf();
        Socialite::shouldReceive('user')->once()->andReturn($socialUser);

        $response = $this->get(route('social.callback', ['provider' => 'google']));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas(
            'error',
            'Провайдер не подтвердил email. Войдите в аккаунт паролем и привяжите соцсеть в личном кабинете.'
        );

        $this->assertDatabaseCount('social_accounts', 0);
    }

    protected function mockSocialUser(string $id, ?string $email, array $raw = []): SocialiteUserContract
    {
        $socialUser = Mockery::mock(SocialiteUserContract::class);
        $socialUser->shouldReceive('getId')->andReturn($id);
        $socialUser->shouldReceive('getEmail')->andReturn($email);
        $socialUser->shouldReceive('getNickname')->andReturn(null);
        $socialUser->shouldReceive('getName')->andReturn('Test Social User');
        $socialUser->shouldReceive('getAvatar')->andReturn(null);

        $socialUser->token = 'fake-token';
        $socialUser->refreshToken = 'fake-refresh-token';
        $socialUser->user = $raw;

        return $socialUser;
    }
}
