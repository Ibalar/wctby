<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'middle_name',
        'phone',
        'avatar',
        'birthday',
        'gender',
        'locale',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthday' => 'date',
        ];
    }

    public function getIsEmailVerifiedAttribute(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function setEmailVerifiedAtAttribute($value): void
    {
        if ($value === null || $value === '' || $value === false || $value === 0 || $value === '0') {
            $this->attributes['email_verified_at'] = null;

            return;
        }

        if ($value === true || $value === 1 || $value === '1') {
            $current = $this->attributes['email_verified_at'] ?? null;
            $this->attributes['email_verified_at'] = $current ?: $this->fromDateTime(now());

            return;
        }

        $this->attributes['email_verified_at'] = $this->fromDateTime($value);
    }

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true)->where('type', 'shipping');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function getFullNameAttribute(): string
    {
        $parts = array_filter([
            $this->first_name,
            $this->last_name,
        ]);

        return ! empty($parts) ? implode(' ', $parts) : $this->name;
    }

    public function getFullNameMiddleAttribute(): string
    {
        $parts = array_filter([
            $this->last_name,
            $this->first_name,
            $this->middle_name,
        ]);

        return ! empty($parts) ? implode(' ', $parts) : $this->name;
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->first_name) {
            return $this->first_name;
        }

        return $this->name;
    }

    public function getInitialsAttribute(): string
    {
        $initials = '';

        if ($this->first_name) {
            $initials .= mb_substr($this->first_name, 0, 1);
        }

        if ($this->last_name) {
            $initials .= mb_substr($this->last_name, 0, 1);
        }

        if (empty($initials) && $this->name) {
            $parts = explode(' ', $this->name);
            foreach ($parts as $part) {
                $initials .= mb_substr($part, 0, 1);
            }
        }

        return mb_strtoupper($initials);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/'.$this->avatar);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->display_name).'&background=random';
    }

    public function hasSocialAccount(string $provider): bool
    {
        return $this->socialAccounts()->where('provider', $provider)->exists();
    }

    public function getSocialAccount(string $provider): ?SocialAccount
    {
        return $this->socialAccounts()->where('provider', $provider)->first();
    }

    public function linkSocialAccount(string $provider, array $data): SocialAccount
    {
        return SocialAccount::createOrUpdate($this->id, $provider, $data);
    }

    public function unlinkSocialAccount(string $provider): bool
    {
        return $this->socialAccounts()->where('provider', $provider)->delete() > 0;
    }
}
