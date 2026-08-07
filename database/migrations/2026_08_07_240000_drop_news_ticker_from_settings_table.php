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
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'news_ticker_total')) {
                $table->dropColumn('news_ticker_total');
            }
            if (Schema::hasColumn('settings', 'news_ticker_status')) {
                $table->dropColumn('news_ticker_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'news_ticker_total')) {
                $table->text('news_ticker_total')->nullable();
            }
            if (! Schema::hasColumn('settings', 'news_ticker_status')) {
                $table->text('news_ticker_status')->nullable();
            }
        });
    }
};
