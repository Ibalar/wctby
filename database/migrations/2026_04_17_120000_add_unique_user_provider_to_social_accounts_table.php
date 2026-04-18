<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Keep only the latest record per (user_id, provider) before adding unique index.
        $duplicates = DB::table('social_accounts')
            ->select('user_id', 'provider', DB::raw('MAX(id) as keep_id'))
            ->groupBy('user_id', 'provider')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('social_accounts')
                ->where('user_id', $duplicate->user_id)
                ->where('provider', $duplicate->provider)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();
        }

        Schema::table('social_accounts', function (Blueprint $table) {
            $table->unique(['user_id', 'provider']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('social_accounts', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'provider']);
        });
    }
};
