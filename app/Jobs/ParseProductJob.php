<?php

namespace App\Jobs;

use App\Models\ParsedItem;
use App\Services\ProductParserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ParseProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(
        public int $parsedItemId,
    ) {}

    public function handle(ProductParserService $parser): void
    {
        $item = ParsedItem::findOrFail($this->parsedItemId);

        Log::info('[ParseProductJob] Starting', ['parsed_item_id' => $item->id, 'url' => $item->source_url]);

        $parser->parseAndCreateProduct($item);

        Log::info('[ParseProductJob] Completed', ['parsed_item_id' => $item->id]);
    }

    public function failed(\Throwable $e): void
    {
        $item = ParsedItem::find($this->parsedItemId);
        if ($item) {
            $item->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
        }

        Log::error('[ParseProductJob] Failed', ['parsed_item_id' => $this->parsedItemId, 'error' => $e->getMessage()]);
    }
}
