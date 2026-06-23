<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductImportExportTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function export_creates_csv_file(): void
    {
        Storage::fake('local');
        Product::factory()->create(['name' => 'Test Product', 'slug' => 'test-product', 'is_active' => true]);

        $this->artisan('products:export', ['--path' => 'exports/test_export.csv'])
            ->assertSuccessful();

        Storage::disk('local')->assertExists('exports/test_export.csv');
    }

    #[Test]
    public function import_creates_new_products(): void
    {
        Storage::fake('local');

        $csv = "name,slug,category_id,base_price,description,is_active,meta_title,meta_description\nNew Product,new-product,,99.99,Test desc,1,,\n";
        Storage::put('imports/test_import.csv', $csv);

        $this->artisan('products:import', ['file' => 'imports/test_import.csv'])
            ->assertSuccessful();

        $this->assertDatabaseHas('products', ['slug' => 'new-product', 'name' => 'New Product']);
    }

    #[Test]
    public function import_updates_existing_products(): void
    {
        Storage::fake('local');
        Product::factory()->create(['name' => 'Old Name', 'slug' => 'existing', 'base_price' => 10]);

        $csv = "name,slug,category_id,base_price,description,is_active,meta_title,meta_description\nUpdated Name,existing,,49.99,Updated desc,1,,\n";
        Storage::put('imports/test_update.csv', $csv);

        $this->artisan('products:import', ['file' => 'imports/test_update.csv'])
            ->assertSuccessful();

        $product = Product::where('slug', 'existing')->first();
        $this->assertSame('Updated Name', $product->name);
        $this->assertSame(49.99, (float) $product->base_price);
    }

    #[Test]
    public function import_skips_empty_slug_rows(): void
    {
        Storage::fake('local');

        $csv = "name,slug,category_id,base_price,description,is_active,meta_title,meta_description\n,no-slug,,0,,0,,\nValid,valid-slug,,50,,1,,\n";
        Storage::put('imports/test_bad.csv', $csv);

        $this->artisan('products:import', ['file' => 'imports/test_bad.csv'])
            ->assertSuccessful();

        $this->assertDatabaseHas('products', ['slug' => 'valid-slug']);
        $this->assertDatabaseMissing('products', ['slug' => 'no-slug']);
    }
}
