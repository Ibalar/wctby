<?php

namespace App\Services;

use App\Models\ParsedItem;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class ProductParserService
{
    protected array $config;

    public function __construct()
    {
        $this->config = config('parsers', []);
    }

    protected function cleanText(?string $text): ?string
    {
        if ($text === null) return null;
        // Удаляем невалидные UTF-8 байты
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        return trim($text);
    }

    protected function cleanArray(array $data): array
    {
        return array_map(function ($value) {
            if (is_string($value)) return $this->cleanText($value);
            if (is_array($value)) return $this->cleanArray($value);
            return $value;
        }, $data);
    }

    public function parseFromUrl(string $url): array
    {
        Log::info('[ProductParser] Fetching URL', ['url' => $url]);

        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language' => 'ru-RU,ru;q=0.9',
        ])->timeout(30)->get($url);

        if (!$response->successful()) {
            throw new \Exception("HTTP {$response->status()}: страница недоступна");
        }

        $html = $response->body();

        if (empty(trim($html))) {
            throw new \Exception('Пустой ответ от сервера');
        }

        $crawler = new Crawler($html);
        $siteCode = $this->detectSite($url);
        $selectors = $this->getSelectors($siteCode);

        $result = $this->extractData($crawler, $selectors);

        if (empty($result['name'])) {
            throw new \Exception('Не удалось извлечь название товара. Проверьте селекторы для сайта ' . $siteCode);
        }

        $result['source_url'] = $url;
        $result['site_code'] = $siteCode;
        $result = $this->cleanArray($result);

        // Извлечение доп. изображений
        $result['images'] = $this->extractImages($crawler, $url);

        // Парсинг характеристик в properties
        $result['properties'] = $this->extractSpecs($crawler, $siteCode, $url);

        Log::info('[ProductParser] Parsed successfully', [
            'url' => $url,
            'site' => $siteCode,
            'name' => $result['name'],
            'price' => $result['price'] ?? 'N/A',
            'images' => count($result['images'] ?? []),
            'specs' => count($result['properties'] ?? []),
        ]);

        return $result;
    }

    public function parseAndCreateProduct(ParsedItem $item): Product
    {
        $item->update(['status' => 'processing']);

        try {
            $data = $this->parseFromUrl($item->source_url);
            $item->update(['raw_data' => $data, 'site_code' => $data['site_code']]);

            // Проверяем, не парсили ли уже этот URL
            $existing = ParsedItem::where('source_url', $item->source_url)
                ->where('status', 'done')
                ->where('id', '!=', $item->id)
                ->first();

            if ($existing && $existing->product_id) {
                $product = Product::find($existing->product_id);
                if ($product) {
                    // Обновляем существующий товар
                    $this->updateProductFromData($product, $data);
                    $item->update(['status' => 'done', 'product_id' => $product->id]);
                    Log::info('[ProductParser] Product updated', [
                        'url' => $item->source_url,
                        'product_id' => $product->id,
                    ]);
                    return $product;
                }
            }

            $product = $this->createProductFromData($data);
            $item->update(['status' => 'done', 'product_id' => $product->id]);

            Log::info('[ProductParser] Product created', [
                'url' => $item->source_url,
                'product_id' => $product->id,
                'product_name' => $product->name,
            ]);

            return $product;
        } catch (\Exception $e) {
            $item->update(['status' => 'failed', 'error_message' => $e->getMessage()]);

            Log::error('[ProductParser] Failed', [
                'url' => $item->source_url,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    protected function detectSite(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);

        // Сначала ищем в БД (пользовательские схемы)
        $dbSites = \App\Models\ParserSite::active()->get();
        foreach ($dbSites as $site) {
            foreach ($site->domains as $domain) {
                if ($domain === '*' || str_contains($host, $domain)) {
                    return $site->code;
                }
            }
        }

        // Fallback: config/parsers.php
        foreach ($this->config['sites'] as $code => $site) {
            foreach ($site['domains'] as $domain) {
                if ($domain === '*' || str_contains($host, $domain)) {
                    return $code;
                }
            }
        }

        return 'generic';
    }

    protected function getSelectors(string $siteCode): array
    {
        // Сначала ищем в БД
        $dbSite = \App\Models\ParserSite::active()->where('code', $siteCode)->first();
        if ($dbSite && !empty($dbSite->selectors)) {
            return $dbSite->selectors;
        }

        // Fallback: config/parsers.php
        $siteConfig = $this->config['sites'][$siteCode] ?? null;

        return $siteConfig['selectors'] ?? $this->config['default_selectors'];
    }

    protected function extractData(Crawler $crawler, array $selectors): array
    {
        $data = [];

        foreach ($selectors as $field => $selectorList) {
            if ($field === 'specs') continue;

            $selectorsArr = is_array($selectorList) ? $selectorList : [$selectorList];

            foreach ($selectorsArr as $selector) {
                $isAttr = str_contains($selector, '::attr(');
                $attr = null;

                if ($isAttr) {
                    preg_match('/::attr\((\w+)\)/', $selector, $m);
                    $attr = $m[1] ?? null;
                    $selector = preg_replace('/::attr\(\w+\)/', '', $selector);
                }

                try {
                    $node = $crawler->filter($selector)->first();
                    if ($node->count() > 0) {
                        $data[$field] = $attr
                            ? $this->cleanText($node->attr($attr))
                            : $this->cleanText($node->text());
                        break;
                    }
                } catch (\Exception) {
                    continue;
                }
            }

            $data[$field] = $data[$field] ?? null;
        }

        if (isset($data['price'])) {
            $data['price'] = preg_replace('/[^\d.,]/', '', $data['price']);
            $data['price'] = str_replace(',', '.', $data['price']);
        }

        return $data;
    }

    protected function extractImages(Crawler $crawler, string $sourceUrl): array
    {
        $images = [];

        // Только изображения товара — внутри masthead/gallery контейнера
        $containerSelectors = [
            '.catalog-masthead',
            '[class*="product-gallery"]',
            '[class*="product-masthead"]',
            '[class*="gallery"]',
            '[itemscope][itemtype*="Product"]',
        ];

        foreach ($containerSelectors as $containerSel) {
            try {
                $container = $crawler->filter($containerSel)->first();
                if ($container->count() === 0) continue;

                // <a> с href внут��и контейнера
                $container->filter('a[href]')->each(
                    function ($node) use (&$images, $sourceUrl) {
                        $href = $node->attr('href');
                        if ($href && (str_contains($href, 'imgproxy') || str_contains($href, '/content/'))) {
                            $resolved = $this->resolveImageUrl($href, $sourceUrl);
                            if ($resolved && !in_array($resolved, $images) && !str_contains($resolved, '/navigation/')) {
                                $images[] = $resolved;
                            }
                        }
                    }
                );

                if (!empty($images)) break;
            } catch (\Exception) {
                continue;
            }
        }

        // Fallback: img внутри контейнера
        if (empty($images)) {
            foreach (['.catalog-masthead', '[class*="product-gallery"]', '[class*="masthead"]'] as $sel) {
                try {
                    $container = $crawler->filter($sel)->first();
                    if ($container->count() > 0) {
                        $container->filter('img')->each(function ($node) use (&$images, $sourceUrl) {
                            $src = $node->attr('src') ?? $node->attr('data-src');
                            if ($src && !str_contains($src, 'logo') && !str_contains($src, 'icon')) {
                                $resolved = $this->resolveImageUrl($src, $sourceUrl);
                                if ($resolved && !in_array($resolved, $images)) {
                                    $images[] = $resolved;
                                }
                            }
                        });
                        if (!empty($images)) break;
                    }
                } catch (\Exception) {}
            }
        }

        Log::info('[ProductParser] Images found', ['count' => count($images)]);

        return array_slice($images, 0, 5);
    }

    protected function extractSpecs(Crawler $crawler, string $siteCode, string $sourceUrl): array
    {
        $specs = [];

        // Onliner: спецификации в <dl> внутри класса offers-description
        $dlSelectors = [
            'dl', 'dl[class*="spec"]', 'dl[class*="offers"]',
            '[class*="offers"] dl', '[class*="spec"] dl', '[class*="detail"] dl',
        ];

        foreach ($dlSelectors as $dlSel) {
            try {
                $dts = $crawler->filter("{$dlSel} dt");
                if ($dts->count() > 0) {
                    $dts->each(function ($dt) use (&$specs) {
                        try {
                            $key = trim(strip_tags($dt->html()));
                            $key = rtrim($key, ":\s");

                            $dd = $dt->nextAll()->filter('dd')->first();
                            if ($dd->count() === 0) {
                                $dd = $dt->closest('dl')->filter('dd')->eq(
                                    $dt->closest('dl')->filter('dt')->previousAll()->count()
                                );
                            }
                            $value = $dd->count() > 0 ? trim(strip_tags($dd->html())) : '';

                            if (!empty($key) && mb_strlen($key) < 100) {
                                $specs[$key] = $value;
                            }
                        } catch (\Exception) {}
                    });

                    Log::info('[ProductParser] Specs from dl', ['selector' => $dlSel, 'count' => count($specs)]);
                    if (count($specs) >= 2) return $specs;
                }
            } catch (\Exception) {}
        }

        // Table структура
        try {
            $rows = $crawler->filter('table tr');
            $rows->each(function ($row) use (&$specs) {
                try {
                    $th = $row->filter('th');
                    $td = $row->filter('td');
                    if ($th->count() >= 1 && $td->count() >= 1) {
                        $key = trim(strip_tags($th->first()->html()));
                        $value = trim(strip_tags($td->first()->html()));
                        $key = rtrim($key, ":\s");
                        if (!empty($key) && mb_strlen($key) < 100) $specs[$key] = $value;
                    } elseif ($td->count() >= 2) {
                        $key = trim(strip_tags($td->eq(0)->html()));
                        $value = trim(strip_tags($td->eq(1)->html()));
                        $key = rtrim($key, ":\s");
                        if (!empty($key) && mb_strlen($key) < 100) $specs[$key] = $value;
                    }
                } catch (\Exception) {}
            });
            Log::info('[ProductParser] Specs from table', ['count' => count($specs)]);
            if (count($specs) >= 2) return $specs;
        } catch (\Exception) {}

        Log::info('[ProductParser] Specs result', ['count' => count($specs), 'sample' => array_slice($specs, 0, 3)]);

        return $specs;
    }

    protected function createProductFromData(array $data): Product
    {
        $product = new Product;
        $this->fillProduct($product, $data, true);
        $product->save();
        $this->downloadImages($product, $data['images'] ?? []);

        return $product;
    }

    protected function updateProductFromData(Product $product, array $data): void
    {
        $this->fillProduct($product, $data, false);
        $product->save();
        $this->downloadImages($product, $data['images'] ?? []);
    }

    protected function fillProduct(Product $product, array $data, bool $isNew): void
    {
        $name = $data['name'] ?? $product->name ?? 'Новый товар';
        $price = !empty($data['price']) ? (float) $data['price'] : 0;

        $product->name = $name;
        $product->base_price = $price > 0 ? $price : ($product->base_price ?? 0);
        $product->short_description = $data['description'] ?? $product->short_description;
        $product->is_active = $product->is_active ?? false;

        if (!empty($data['properties'])) {
            $existing = $product->properties ?? [];
            $merged = array_merge($existing, $data['properties']);
            $product->properties = $merged;
        }

        if ($isNew) {
            if (empty($product->category_id)) $product->category_id = null;

            $slug = Str::slug($name);
            $originalSlug = $slug;
            $counter = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }
            $product->slug = $slug;
            $product->sku = 'parsed-' . Str::random(8);
        }
    }

    protected function downloadImages(Product $product, array $images): void
    {
        foreach ($images as $imageUrl) {
            try {
                // Скачиваем файл локально, затем добавляем в media-library
                $tempPath = tempnam(sys_get_temp_dir(), 'parser_');
                $response = Http::timeout(30)->get($imageUrl);

                if ($response->successful()) {
                    file_put_contents($tempPath, $response->body());

                    $media = $product
                        ->addMedia($tempPath)
                        ->preservingOriginal()
                        ->toMediaCollection('images');

                    Log::info('[ProductParser] Image downloaded', [
                        'url' => $imageUrl,
                        'media_id' => $media->id,
                    ]);
                }

                @unlink($tempPath);
            } catch (\Exception $e) {
                Log::warning('[ProductParser] Image download failed', [
                    'url' => $imageUrl,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    protected function resolveImageUrl(string $src, string $sourceUrl): ?string
    {
        if (empty($src)) return null;

        if (preg_match('#^https?://#', $src)) return $src;

        if (str_starts_with($src, '//')) return 'https:' . $src;

        $base = parse_url($sourceUrl);
        if (!$base || empty($base['scheme']) || empty($base['host'])) return null;

        $path = str_starts_with($src, '/') ? $src : '/' . $src;

        return $base['scheme'] . '://' . $base['host'] . $path;
    }
}
