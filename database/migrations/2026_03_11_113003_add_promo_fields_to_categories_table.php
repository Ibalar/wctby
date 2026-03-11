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
        Schema::table('categories', function (Blueprint $table) {
            Schema::table('categories', function (Blueprint $table) {
                // Switcher для активности промо-блока
                $table->boolean('promo_active')->default(false)->after('is_active');

                // Заголовок промо-блока
                $table->string('promo_title')->nullable()->after('promo_active');

                // Подзаголовок промо-блока
                $table->string('promo_subtitle')->nullable()->after('promo_title');

                // Текст кнопки
                $table->string('promo_button_text')->nullable()->after('promo_subtitle');

                // Ссылка на товар (может быть внешней или внутренней)
                $table->string('promo_link')->nullable()->after('promo_button_text');

                // Связь с конкретным товаром (опционально)
                $table->foreignId('promo_product_id')
                    ->nullable()
                    ->after('promo_link')
                    ->constrained('products')
                    ->nullOnDelete();

                // Дополнительные поля для кастомизации
                $table->string('promo_badge_text')->nullable()->after('promo_product_id');
                $table->string('promo_badge_color')->default('danger')->after('promo_badge_text');
                $table->string('promo_old_price')->nullable()->after('promo_badge_color');
                $table->string('promo_new_price')->nullable()->after('promo_old_price');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'promo_active',
                'promo_title',
                'promo_subtitle',
                'promo_button_text',
                'promo_link',
                'promo_product_id',
                'promo_badge_text',
                'promo_badge_color',
                'promo_old_price',
                'promo_new_price',
            ]);
        });
    }
};
