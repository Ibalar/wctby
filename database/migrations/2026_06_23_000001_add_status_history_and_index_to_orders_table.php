<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'status_history')) {
                $table->json('status_history')->nullable()->after('status');
            }

            if (!$this->hasIndex('orders', 'status')) {
                $table->index('status', 'orders_status_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'status_history')) {
                $table->dropIndex('orders_status_index');
                $table->dropColumn('status_history');
            }
        });
    }

    protected function hasIndex(string $table, string $column): bool
    {
        $indexes = Schema::getIndexes($table);
        foreach ($indexes as $index) {
            if (in_array($column, $index['columns'])) {
                return true;
            }
        }
        return false;
    }
};
