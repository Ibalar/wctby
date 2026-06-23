<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ParsedItem extends Model
{
    protected $fillable = [
        'source_url', 'site_code', 'status', 'raw_data', 'product_id', 'error_message',
    ];

    protected $casts = [
        'raw_data' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (ParsedItem $item) {
            // Одиночный URL
            if (!empty($item->source_url) && empty($item->status)) {
                $item->status = 'pending';
            }
        });

        static::created(function (ParsedItem $item) {
            // Обработка нескольких URL из поля urls (только при создании)
            if (request()->has('urls') && !empty(request('urls'))) {
                $urls = array_filter(array_map('trim', explode("\n", request('urls'))));
                $siteCode = request('site_code');

                foreach ($urls as $url) {
                    if (filter_var($url, FILTER_VALIDATE_URL) && $url !== $item->source_url) {
                        static::create([
                            'source_url' => $url,
                            'site_code' => $siteCode ?: null,
                            'status' => 'pending',
                        ]);
                    }
                }

                Log::info('[ParsedItem] Batch URLs created', ['count' => count($urls)]);
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
