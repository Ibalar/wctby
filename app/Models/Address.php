<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'city',
        'street',
        'house',
        'apartment',
        'postal_code',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope для адресов доставки
     */
    public function scopeShipping($query)
    {
        return $query->where('type', 'shipping');
    }

    /**
     * Scope для платёжных адресов
     */
    public function scopeBilling($query)
    {
        return $query->where('type', 'billing');
    }

    /**
     * Scope для адреса по умолчанию
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Получить полный адрес строкой
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->postal_code,
            $this->city,
            $this->street,
            $this->house ? 'д. ' . $this->house : null,
            $this->apartment ? 'кв. ' . $this->apartment : null,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Установить как адрес по умолчанию
     */
    public function setAsDefault(): void
    {
        static::where('user_id', $this->user_id)
            ->where('type', $this->type)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        $this->update(['is_default' => true]);
    }
}
