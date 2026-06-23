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
            if (!empty($item->source_url) && empty($item->status)) {
                $item->status = 'pending';
            }

            // Обработка нескольких URL из виртуального поля urls (до сохранения)
            if (request()->has('urls') && !empty(trim(request('urls', '')))) {
                $urls = array_filter(array_map('trim', explode("\n", request('urls'))));
                $siteCode = request('site_code');
                $added = 0;

                foreach ($urls as $url) {
                    if (filter_var($url, FILTER_VALIDATE_URL) && $url !== ($item->source_url ?? '')) {
                        static::create([
                            'source_url' => $url,
                            'site_code' => $siteCode ?: null,
                            'status' => 'pending',
                        ]);
                        $added++;
                    }
                }

                if ($added > 0) {
                    Log::info('[ParsedItem] Batch URLs created', ['count' => $added]);

                    // Если это был только batch без одиночного URL, отменяем сохранение текущей модели
                    if (empty($item->source_url)) {
                        return false;
                    }
                }
            }

            // urls — виртуальное поле, не сохраняем в БД
            unset($item->attributes['urls']);
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
