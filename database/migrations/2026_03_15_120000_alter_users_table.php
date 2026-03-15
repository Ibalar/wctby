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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('middle_name')->nullable()->after('last_name');
            $table->string('phone')->unique()->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->date('birthday')->nullable()->after('avatar');
            $table->string('gender')->nullable()->after('birthday');
            $table->string('locale')->default('ru')->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_phone_unique');

            $table->dropColumn([
                'first_name',
                'last_name',
                'middle_name',
                'phone',
                'avatar',
                'birthday',
                'gender',
                'locale',
            ]);
        });
    }
};
