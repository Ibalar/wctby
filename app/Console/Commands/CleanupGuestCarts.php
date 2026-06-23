<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupGuestCarts extends Command
{
    protected $signature = 'cart:cleanup {--expired : Delete only expired guest carts (based on expires_at)}';

    protected $description = 'Clean up expired guest carts and their items';

    public function handle(): int
    {
        $this->info('Cleaning up expired guest carts...');

        $query = Cart::whereNull('user_id')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now());

        $expiredCount = $query->count();

        if ($expiredCount === 0) {
            $this->info('No expired guest carts found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$expiredCount} expired guest carts.");

        DB::transaction(function () use ($query) {
            $cartIds = $query->pluck('id');

            CartItem::whereIn('cart_id', $cartIds)->delete();

            Cart::whereIn('id', $cartIds)->delete();
        });

        $this->info("Successfully deleted {$expiredCount} expired guest carts and their items.");

        return Command::SUCCESS;
    }
}
