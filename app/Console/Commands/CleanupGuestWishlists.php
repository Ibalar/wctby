<?php

namespace App\Console\Commands;

use App\Models\Wishlist;
use Illuminate\Console\Command;

class CleanupGuestWishlists extends Command
{
    protected $signature = 'wishlist:cleanup {--days=30 : Delete guest wishlists older than this many days}';

    protected $description = 'Clean up old guest wishlist entries';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);

        $this->info("Deleting guest wishlists older than {$days} days (before {$cutoffDate})...");

        $deleted = Wishlist::whereNull('user_id')
            ->where('created_at', '<', $cutoffDate)
            ->delete();

        $this->info("Deleted {$deleted} guest wishlist entries.");

        return Command::SUCCESS;
    }
}
