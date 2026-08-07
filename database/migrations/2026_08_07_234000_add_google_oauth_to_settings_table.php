<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'google_client_id')) {
                $table->text('google_client_id')->nullable()->after('disqus_code');
            }
            if (! Schema::hasColumn('settings', 'google_client_secret')) {
                $table->text('google_client_secret')->nullable()->after('google_client_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            foreach (['google_client_id', 'google_client_secret'] as $column) {
                if (Schema::hasColumn('settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
