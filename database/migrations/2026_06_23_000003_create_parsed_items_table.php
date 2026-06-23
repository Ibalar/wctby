<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parsed_items', function (Blueprint $table) {
            $table->id();
            $table->string('source_url');
            $table->string('site_code')->nullable();
            $table->string('status')->default('pending'); // pending, processing, done, failed
            $table->json('raw_data')->nullable();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parsed_items');
    }
};
