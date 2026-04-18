<?php

namespace Tests\Feature;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProfileAuthFlowsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_email_change_resets_verification_and_sends_notification(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'old@example.test',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Updated User',
            'email' => 'new@example.test',
            'first_name' => 'Updated',
            'last_name' => 'User',
            'middle_name' => null,
            'phone' => null,
            'birthday' => null,
            'gender' => null,
        ]);

        $response->assertRedirect(route('profile.index'));
        $response->assertSessionHas('success', 'Профиль обновлён. Подтвердите новый email по ссылке в письме.');

        $user->refresh();

        $this->assertSame('new@example.test', $user->email);
        $this->assertNull($user->email_verified_at);

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_unlink_social_rejects_unsupported_provider(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete(route('profile.social.unlink', ['provider' => 'unsupported']));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Неподдерживаемый провайдер авторизации');
    }

    public function test_user_can_unlink_supported_social_provider(): void
    {
        $user = User::factory()->create();

        SocialAccount::query()->create([
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => 'g-001',
        ]);

        $response = $this->actingAs($user)->delete(route('profile.social.unlink', ['provider' => 'google']));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Аккаунт Google отвязан');
        $this->assertDatabaseMissing('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => 'g-001',
        ]);
    }
}
