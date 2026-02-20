<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->string('item_type'); // sku/bundle
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('quantity');
            $table->decimal('line_total', 12, 2);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
