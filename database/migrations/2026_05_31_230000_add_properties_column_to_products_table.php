<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('products', 'properties')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->json('properties')->nullable()->after('flags');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('products', 'properties')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('properties');
        });
    }
};
