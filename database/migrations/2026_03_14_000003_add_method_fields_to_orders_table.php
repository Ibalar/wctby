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
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'payment_method_code')) {
                $table->string('payment_method_code')->nullable()->after('total');
            }

            if (! Schema::hasColumn('orders', 'payment_method_name')) {
                $table->string('payment_method_name')->nullable()->after('payment_method_code');
            }

            if (! Schema::hasColumn('orders', 'delivery_method_code')) {
                $table->string('delivery_method_code')->nullable()->after('payment_method_name');
            }

            if (! Schema::hasColumn('orders', 'delivery_method_name')) {
                $table->string('delivery_method_name')->nullable()->after('delivery_method_code');
            }

            if (! Schema::hasColumn('orders', 'delivery_price')) {
                $table->decimal('delivery_price', 12, 2)->default(0)->after('delivery_method_name');
            }
        });

        if (Schema::hasColumn('orders', 'payment_method') && ! Schema::hasColumn('orders', 'payment_method_code')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->renameColumn('payment_method', 'payment_method_code');
            });
        }

        if (Schema::hasColumn('orders', 'delivery_method') && ! Schema::hasColumn('orders', 'delivery_method_code')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->renameColumn('delivery_method', 'delivery_method_code');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'delivery_price')) {
                $table->dropColumn('delivery_price');
            }

            if (Schema::hasColumn('orders', 'delivery_method_name')) {
                $table->dropColumn('delivery_method_name');
            }

            if (Schema::hasColumn('orders', 'delivery_method_code')) {
                $table->dropColumn('delivery_method_code');
            }

            if (Schema::hasColumn('orders', 'payment_method_name')) {
                $table->dropColumn('payment_method_name');
            }

            if (Schema::hasColumn('orders', 'payment_method_code')) {
                $table->dropColumn('payment_method_code');
            }
        });
    }
};
