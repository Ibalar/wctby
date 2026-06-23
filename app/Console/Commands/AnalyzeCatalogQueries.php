<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AnalyzeCatalogQueries extends Command
{
    protected $signature = 'catalog:analyze-queries {--category= : Category ID to analyze}';
    protected $description = 'Analyze performance of catalog queries using EXPLAIN';

    public function handle(): int
    {
        $this->info('Analyzing catalog query performance...');
        $this->newLine();

        $categoryId = $this->option('category');
        
        if ($categoryId) {
            $category = Category::find($categoryId);
            if (!$category) {
                $this->error("Category {$categoryId} not found");
                return Command::FAILURE;
            }
        } else {
            $category = Category::where('is_active', true)->first();
            if (!$category) {
                $this->warn('No active categories found');
                return Command::SUCCESS;
            }
        }

        $this->info("Analyzing queries for category: {$category->name} (ID: {$category->id})");
        $this->newLine();

        $categoryIds = $this->getDescendantIds($category->id);

        $this->analyzeProductListQuery($categoryIds);
        $this->analyzePriceStatsQuery($categoryIds);
        $this->analyzeFlagsQuery($categoryIds);
        $this->analyzeSkuMinPriceQuery();

        return Command::SUCCESS;
    }

    private function getDescendantIds(int $categoryId): array
    {
        $allIds = collect([$categoryId]);
        $currentLevel = collect([$categoryId]);

        while ($currentLevel->isNotEmpty()) {
            $children = Category::query()
                ->whereIn('parent_id', $currentLevel)
                ->pluck('id');

            if ($children->isEmpty()) {
                break;
            }

            $allIds = $allIds->merge($children)->unique()->values();
            $currentLevel = $children;
        }

        return $allIds->toArray();
    }

    private function analyzeProductListQuery(array $categoryIds): void
    {
        $this->task('Product list query', function () use ($categoryIds) {
            $query = Product::query()
                ->whereIn('category_id', $categoryIds)
                ->where('is_active', true)
                ->with(['skus' => fn ($q) => $q->where('is_active', true)])
                ->with('media');

            $this->explainQuery($query->toSql(), $query->getBindings());
        });
    }

    private function analyzePriceStatsQuery(array $categoryIds): void
    {
        $this->task('Price statistics query', function () use ($categoryIds) {
            $query = Product::query()
                ->leftJoinSub(
                    DB::table('skus')
                        ->selectRaw('product_id, MIN(price) as min_price')
                        ->where('is_active', true)
                        ->groupBy('product_id'),
                    'sku_prices',
                    'sku_prices.product_id',
                    '=',
                    'products.id'
                )
                ->whereIn('products.category_id', $categoryIds)
                ->where('products.is_active', true)
                ->selectRaw('MIN(COALESCE(sku_prices.min_price, products.base_price)) as min_price, MAX(COALESCE(sku_prices.min_price, products.base_price)) as max_price');

            $this->explainQuery($query->toSql(), $query->getBindings());
        });
    }

    private function analyzeFlagsQuery(array $categoryIds): void
    {
        $this->task('Flags query', function () use ($categoryIds) {
            $query = Product::query()
                ->whereIn('category_id', $categoryIds)
                ->where('is_active', true)
                ->select('flags');

            $this->explainQuery($query->toSql(), $query->getBindings());
        });
    }

    private function analyzeSkuMinPriceQuery(): void
    {
        $this->task('SKU min price subquery', function () {
            $sql = 'SELECT product_id, MIN(price) as min_price FROM skus WHERE is_active = ? GROUP BY product_id';
            $bindings = [true];

            $this->explainQuery($sql, $bindings);
        });
    }

    private function explainQuery(string $sql, array $bindings): void
    {
        $this->newLine();
        $this->line('<fg=gray>SQL:</> ' . $sql);
        
        if (!empty($bindings)) {
            $this->line('<fg=gray>Bindings:</> ' . json_encode($bindings));
        }

        $this->newLine();
        $this->line('<fg=cyan>EXPLAIN:</>');

        try {
            $explainSql = 'EXPLAIN ' . $sql;
            $results = DB::select($explainSql, $bindings);

            if (empty($results)) {
                $this->warn('No EXPLAIN results');
                return;
            }

            $headers = array_keys((array) $results[0]);
            $rows = array_map(fn ($row) => (array) $row, $results);

            $this->table($headers, $rows);

            $this->analyzeExplainResults($results);
        } catch (\Exception $e) {
            $this->error('EXPLAIN failed: ' . $e->getMessage());
        }
    }

    private function analyzeExplainResults(array $results): void
    {
        $this->newLine();
        $this->line('<fg=cyan>Analysis:</>');

        foreach ($results as $row) {
            $row = (array) $row;

            if (isset($row['type']) && in_array($row['type'], ['ALL', 'index'])) {
                $this->warn("  ⚠ Full table scan detected on table: " . ($row['table'] ?? 'unknown'));
                $this->warn("    Type: {$row['type']}");
                
                if (isset($row['possible_keys']) && empty($row['possible_keys'])) {
                    $this->warn("    No indexes available for this query");
                }
            }

            if (isset($row['rows']) && $row['rows'] > 1000) {
                $this->warn("  ⚠ High row count: {$row['rows']} rows examined");
            }

            if (isset($row['Extra'])) {
                $extra = $row['Extra'];
                
                if (str_contains($extra, 'Using filesort')) {
                    $this->warn("  ⚠ Using filesort (consider adding ORDER BY index)");
                }
                
                if (str_contains($extra, 'Using temporary')) {
                    $this->warn("  ⚠ Using temporary table (query may be slow)");
                }

                if (str_contains($extra, 'Using index')) {
                    $this->info("  ✓ Using index (good)");
                }
            }

            if (isset($row['key']) && !empty($row['key'])) {
                $this->info("  ✓ Using index: {$row['key']}");
            }
        }
    }
}
