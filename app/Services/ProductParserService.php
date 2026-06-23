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

        Log::info('[ProductParser] Parsed successfully', [
            'url' => $url,
            'site' => $siteCode,
            'name' => $result['name'],
            'price' => $result['price'] ?? 'N/A',
        ]);

        return $result;
    }

    public function parseAndCreateProduct(ParsedItem $item): Product
    {
        $item->update(['status' => 'processing']);

        try {
            $data = $this->parseFromUrl($item->source_url);

            $item->update(['raw_data' => $data, 'site_code' => $data['site_code']]);

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
        $siteConfig = $this->config['sites'][$siteCode] ?? null;

        return $siteConfig['selectors'] ?? $this->config['default_selectors'];
    }

    protected function extractData(Crawler $crawler, array $selectors): array
    {
        $data = [];

        foreach ($selectors as $field => $selectorList) {
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
                            ? $node->attr($attr)
                            : trim($node->text());
                        break;
                    }
                } catch (\Exception) {
                    continue;
                }
            }

            $data[$field] = $data[$field] ?? null;
        }

        // Clean price
        if (isset($data['price'])) {
            $data['price'] = preg_replace('/[^\d.,]/', '', $data['price']);
            $data['price'] = str_replace(',', '.', $data['price']);
        }

        return $data;
    }

    protected function createProductFromData(array $data): Product
    {
        $name = $data['name'] ?? 'Новый товар';
        $slug = Str::slug($name);
        $price = !empty($data['price']) ? (float) $data['price'] : 0;

        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $product = Product::create([
            'name' => $name,
            'slug' => $slug,
            'sku' => 'parsed-' . Str::random(8),
            'base_price' => $price > 0 ? $price : 0,
            'description' => $data['description'] ?? null,
            'is_active' => false,
        ]);

        if (!empty($data['image'])) {
            $imageUrl = $this->resolveImageUrl($data['image'], $data['source_url'] ?? '');

            if ($imageUrl) {
                try {
                    $product
                        ->addMediaFromUrl($imageUrl)
                        ->toMediaCollection('images');

                    Log::info('[ProductParser] Image downloaded', ['url' => $imageUrl]);
                } catch (\Exception $e) {
                    Log::warning('[ProductParser] Image download failed', [
                        'url' => $imageUrl,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        return $product;
    }

    protected function resolveImageUrl(string $src, string $sourceUrl): ?string
    {
        if (empty($src)) {
            return null;
        }

        // Already absolute
        if (preg_match('#^https?://#', $src)) {
            return $src;
        }

        // Protocol-relative
        if (str_starts_with($src, '//')) {
            return 'https:' . $src;
        }

        // Relative — resolve against source URL
        $base = parse_url($sourceUrl);
        if (!$base || empty($base['scheme']) || empty($base['host'])) {
            return null;
        }

        $path = str_starts_with($src, '/') ? $src : '/' . $src;

        return $base['scheme'] . '://' . $base['host'] . $path;
    }
}
