<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('sub_categories', 'show_on_home')) {
            Schema::table('sub_categories', function (Blueprint $table) {
                $table->string('show_on_home')->default('Hide')->after('show_on_menu');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('sub_categories', 'show_on_home')) {
            Schema::table('sub_categories', function (Blueprint $table) {
                $table->dropColumn('show_on_home');
            });
        }
    }
};
