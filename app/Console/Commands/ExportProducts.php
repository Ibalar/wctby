<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ExportProducts extends Command
{
    protected $signature = 'products:export {--path= : Custom export path}';

    protected $description = 'Export all products to CSV';

    public function handle(): int
    {
        $path = $this->option('path') ?: 'exports/products_' . now()->format('Ymd_His') . '.csv';
        $fullPath = Storage::path($path);

        Storage::makeDirectory(dirname($path));

        $handle = fopen($fullPath, 'w');
        fputcsv($handle, ['name', 'slug', 'category_id', 'base_price', 'description', 'is_active', 'meta_title', 'meta_description']);

        $count = 0;
        Product::query()->chunk(500, function ($products) use ($handle, &$count) {
            foreach ($products as $product) {
                fputcsv($handle, [
                    $product->name,
                    $product->slug,
                    $product->category_id,
                    $product->base_price,
                    $product->description,
                    $product->is_active ? '1' : '0',
                    $product->meta_title ?? '',
                    $product->meta_description ?? '',
                ]);
                $count++;
            }
        });

        fclose($handle);

        Log::info('[products:export] Export completed', ['path' => $path, 'count' => $count]);
        $this->info("Exported {$count} products to {$fullPath}");

        return self::SUCCESS;
    }
}
