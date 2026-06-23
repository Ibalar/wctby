<?php

declare(strict_types=1);

use App\Jobs\ParseProductJob;
use App\Models\ParsedItem;
use Illuminate\Support\Facades\Route;
use UniSharp\LaravelFilemanager\Lfm;

Route::prefix('laravel-filemanager')->group(function () {
    Lfm::routes();
});

Route::get('/resource/parsed-item-resource/parse-all', function () {
    $pending = ParsedItem::pending()->get();
    $count = 0;

    foreach ($pending as $item) {
        ParseProductJob::dispatch($item->id);
        $count++;
    }

    return redirect()->back()->with('success', "{$count} задач отправлено в очередь");
})->name('moonshine.parser.run');

Route::get('/resource/parsed-item-resource/reparse/{id}', function (int $id) {
    $item = ParsedItem::findOrFail($id);
    $item->update(['status' => 'pending', 'error_message' => null]);
    ParseProductJob::dispatch($item->id);

    return redirect()->back()->with('success', 'Парсинг перезапущен');
})->name('moonshine.parser.reparse');
