<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportProducts extends Command
{
    protected $signature = 'products:import
        {file : Path to CSV file in storage/app}
        {--map= : Column mapping, e.g. name:col_a,sku:col_b}
        {--dry-run : Validate only, do not save}';

    protected $description = 'Import products from CSV. Key field: SKU.';

    protected array $map = [
        'name' => 'name',
        'sku' => 'sku',
        'category_id' => 'category_id',
        'base_price' => 'base_price',
        'description' => 'description',
        'is_active' => 'is_active',
        'meta_title' => 'meta_title',
        'meta_description' => 'meta_description',
    ];

    public function handle(): int
    {
        $filePath = Storage::path($this->argument('file'));

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return self::FAILURE;
        }

        $this->parseColumnMapping();

        $handle = fopen($filePath, 'r');
        $headers = fgetcsv($handle);

        if (!$headers) {
            $this->error('Empty or invalid CSV');
            return self::FAILURE;
        }

        $this->info('CSV columns: ' . implode(', ', $headers));
        $this->info('Mapping: ' . json_encode($this->map));

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        $rowNum = 1;
        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            $data = array_combine($headers, $row);

            $productData = $this->applyMapping($data);

            if (empty($productData['sku']) || empty($productData['name'])) {
                $skipped++;
                $errors[] = "Row {$rowNum}: missing SKU or name";
                continue;
            }

            if (!empty($productData['category_id'])) {
                if (!Category::find($productData['category_id'])) {
                    $skipped++;
                    $errors[] = "Row {$rowNum}: category_id {$productData['category_id']} not found";
                    continue;
                }
            }

            $productData['slug'] = !empty($productData['slug'])
                ? Str::slug($productData['slug'])
                : Str::slug($productData['name']);

            if ($this->option('dry-run')) {
                $this->line("Row {$rowNum}: SKU={$productData['sku']}, name={$productData['name']} — valid");
                continue;
            }

            $product = Product::where('sku', $productData['sku'])->first();
            $isNew = !$product;

            if (!$product) {
                $product = new Product;
            }

            $product->fill($productData);
            $product->save();

            if ($isNew) {
                $created++;
                Log::info('[products:import] Created', ['sku' => $productData['sku'], 'name' => $productData['name']]);
            } else {
                $updated++;
                Log::info('[products:import] Updated', ['sku' => $productData['sku'], 'name' => $productData['name']]);
            }
        }

        fclose($handle);

        $this->info("Import complete: {$created} created, {$updated} updated, {$skipped} skipped");
        if ($errors) {
            $this->warn('Errors:');
            foreach ($errors as $error) {
                $this->line("  - {$error}");
            }
        }

        return self::SUCCESS;
    }

    protected function parseColumnMapping(): void
    {
        $mapArg = $this->option('map');
        if (!$mapArg) return;

        foreach (explode(',', $mapArg) as $pair) {
            $parts = explode(':', trim($pair), 2);
            if (count($parts) === 2 && isset($this->map[$parts[0]])) {
                $this->map[$parts[0]] = $parts[1];
            }
        }
    }

    protected function applyMapping(array $row): array
    {
        $result = [];
        foreach ($this->map as $field => $colName) {
            $result[$field] = $row[$colName] ?? null;
        }
        return $result;
    }
}
