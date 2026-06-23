<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Fields\File;
use MoonShine\UI\Fields\Hidden;
use MoonShine\UI\Fields\Select;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ImportProductsPage extends Page
{
    protected string $title = 'Импорт товаров';

    protected string $subtitle = 'Загрузка CSV с маппингом колонок';

    public function __invoke(): Response
    {
        return $this->render();
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function components(): iterable
    {
        $step = request('step', 'upload');
        $filePath = session('import_csv_path');

        if ($step === 'mapping' && $filePath && Storage::exists($filePath)) {
            return $this->mappingComponents($filePath);
        }

        if ($step === 'result') {
            return $this->resultComponents();
        }

        return $this->uploadComponents();
    }

    protected function uploadComponents(): array
    {
        return [
            Box::make([
                FormBuilder::make(url('/admin/import/upload'))
                    ->fields([
                        File::make('CSV-файл', 'csv_file')
                            ->required()
                            ->allowedExtensions(['csv']),
                    ])
                    ->submit('Загрузить и настроить маппинг', ['class' => 'btn-primary']),
            ]),
        ];
    }

    protected function mappingComponents(string $filePath): array
    {
        $handle = fopen(Storage::path($filePath), 'r');
        $headers = fgetcsv($handle);
        $preview = [];
        for ($i = 0; $i < min(3, count(file(Storage::path($filePath))) - 1); $i++) {
            $row = fgetcsv($handle);
            if ($row) $preview[] = array_combine($headers, $row);
        }
        fclose($handle);

        $productFields = [
            '' => '— Пропустить —',
            'name' => 'Название*',
            'sku' => 'SKU / Артикул*',
            'category_id' => 'ID категории',
            'base_price' => 'Базовая цена',
            'description' => 'Описание',
            'is_active' => 'Активен (1/0)',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
        ];

        $mappingFields = [];
        foreach ($headers as $col) {
            $mappingFields[] = Select::make("Колонка «{$col}»", "map[{$col}]")
                ->options($productFields)
                ->nullable();
        }

        $components = [
            Box::make([
                FormBuilder::make(url('/admin/import/run'))
                    ->fields([
                        Hidden::make('file', 'file')->setValue($filePath),
                        ...$mappingFields,
                    ])
                    ->submit('Запустить импорт', ['class' => 'btn-primary']),
            ]),
        ];

        return $components;
    }

    protected function resultComponents(): array
    {
        $result = session('import_result', []);
        return [
            Box::make([
                "Импорт завершён: {$result['created']} создано, {$result['updated']} обновлено, {$result['skipped']} пропущено.",
            ]),
            ActionButton::make('Ещё импорт', url('/admin/import')),
        ];
    }

    public function upload(Request $request)
    {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt']);
        $path = 'imports/' . uniqid('import_', true) . '.csv';
        Storage::put($path, file_get_contents($request->file('csv_file')->path()));
        session(['import_csv_path' => $path]);

        return redirect()->to(url('/admin/import?step=mapping'));
    }

    public function run(Request $request)
    {
        $filePath = $request->input('file');
        $mapConfig = $request->input('map', []);

        $mapParts = [];
        foreach ($mapConfig as $col => $field) {
            if ($field) {
                $mapParts[] = "{$field}:{$col}";
            }
        }

        Artisan::call('products:import', [
            'file' => $filePath,
            '--map' => implode(',', $mapParts),
        ]);

        $output = Artisan::output();

        Log::info('[ImportProductsPage] Import completed', ['output' => $output]);

        $created = preg_match('/(\d+) created/', $output, $m) ? (int) $m[1] : 0;
        $updated = preg_match('/(\d+) updated/', $output, $m) ? (int) $m[1] : 0;
        $skipped = preg_match('/(\d+) skipped/', $output, $m) ? (int) $m[1] : 0;

        session(['import_result' => compact('created', 'updated', 'skipped')]);
        session()->forget('import_csv_path');

        return redirect()->to(url('/admin/import?step=result'));
    }
}
