<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImportProducts extends Command
{
    protected $signature = 'products:import {file : Path to CSV file in storage/app}';

    protected $description = 'Import products from CSV';

    public function handle(): int
    {
        $filePath = Storage::path($this->argument('file'));

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return self::FAILURE;
        }

        $handle = fopen($filePath, 'r');
        $headers = fgetcsv($handle);

        $created = 0;
        $updated = 0;
        $errors = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($headers, $row);

            if (empty($data['slug']) || empty($data['name'])) {
                $errors++;
                continue;
            }

            $product = Product::where('slug', $data['slug'])->first();
            $isNew = !$product;

            if (!$product) {
                $product = new Product;
                $product->slug = $data['slug'];
            }

            $product->name = $data['name'];
            $product->category_id = !empty($data['category_id']) && Category::find($data['category_id'])
                ? (int) $data['category_id']
                : $product->category_id;
            $product->base_price = !empty($data['base_price']) ? (float) $data['base_price'] : $product->base_price;
            $product->description = $data['description'] ?? $product->description;
            $product->is_active = !empty($data['is_active']) && $data['is_active'] !== '0';
            $product->meta_title = $data['meta_title'] ?? $product->meta_title;
            $product->meta_description = $data['meta_description'] ?? $product->meta_description;

            $product->save();

            if ($isNew) {
                $created++;
            } else {
                $updated++;
            }
        }

        fclose($handle);

        Log::info('[products:import] Import completed', [
            'file' => $this->argument('file'),
            'created' => $created,
            'updated' => $updated,
            'errors' => $errors,
        ]);

        $this->info("Import complete: {$created} created, {$updated} updated, {$errors} errors");

        return self::SUCCESS;
    }
}
