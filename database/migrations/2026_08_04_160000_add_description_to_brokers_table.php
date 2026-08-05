<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brokers', function (Blueprint $table) {
            if (! Schema::hasColumn('brokers', 'description')) {
                $table->longText('description')->nullable()->after('short_description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('brokers', function (Blueprint $table) {
            if (Schema::hasColumn('brokers', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
