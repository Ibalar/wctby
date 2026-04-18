<?php

namespace Tests\Unit;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialAccountModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_or_update_keeps_single_record_per_user_and_provider(): void
    {
        $user = User::factory()->create();

        SocialAccount::createOrUpdate($user->id, 'google', [
            'provider_id' => 'google-1',
            'nickname' => 'first',
        ]);

        SocialAccount::createOrUpdate($user->id, 'google', [
            'provider_id' => 'google-2',
            'nickname' => 'second',
        ]);

        $records = SocialAccount::query()
            ->where('user_id', $user->id)
            ->where('provider', 'google')
            ->get();

        $this->assertCount(1, $records);
        $this->assertSame('google-2', $records->first()->provider_id);
        $this->assertSame('second', $records->first()->nickname);
    }
}
