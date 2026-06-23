<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportDemoData extends Command
{
    protected $signature = 'demo:import';

    protected $description = 'Import demo categories and products from parsed JSON';

    public function handle(): int
    {
        $file = base_path('demo/demo_data.json');

        if (!file_exists($file)) {
            $this->error("File not found: {$file}. Run demo/parse_xlsx.py first.");
            return self::FAILURE;
        }

        $data = json_decode(file_get_contents($file), true);
        $categories = $data['categories'] ?? [];
        $products = $data['products'] ?? [];

        $this->info('Importing ' . count($categories) . ' categories and ' . count($products) . ' products...');

        $catModels = [];
        foreach ($categories as $i => $catName) {
            $slug = Str::slug($catName);
            $cat = Category::firstOrCreate(
                ['slug' => $slug],
                ['name' => $catName, 'is_active' => true, 'parent_id' => null]
            );
            $catModels[$catName] = $cat;
            $this->line("  [{$i}] {$catName}");
        }

        $bar = $this->output->createProgressBar(count($products));
        $created = 0;

        foreach ($products as $data) {
            $catName = $data['category'] ?? '';
            if (!isset($catModels[$catName])) continue;

            $name = $data['name'];
            $slug = Str::slug($name);
            $sku = !empty($data['sku']) ? $data['sku'] : 'demo-' . Str::random(6);

            $originalSlug = $slug;
            $counter = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            Product::create([
                'category_id' => $catModels[$catName]->id,
                'name' => $name,
                'slug' => $slug,
                'sku' => $sku,
                'base_price' => (float) $data['price'],
                'short_description' => null,
                'is_active' => true,
            ]);

            $created++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Done: {$created} products created in " . count($catModels) . ' categories');

        return self::SUCCESS;
    }
}
