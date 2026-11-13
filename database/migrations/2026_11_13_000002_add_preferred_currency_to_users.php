<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'preferred_currency')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('preferred_currency', 3)
                    ->default('USD');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'preferred_currency')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('preferred_currency');
            });
        }
    }
};
