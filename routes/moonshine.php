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
