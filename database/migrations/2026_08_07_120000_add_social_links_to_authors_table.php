<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            if (! Schema::hasColumn('authors', 'twitter_url')) {
                $table->string('twitter_url', 255)->nullable()->after('bio');
            }
            if (! Schema::hasColumn('authors', 'linkedin_url')) {
                $table->string('linkedin_url', 255)->nullable()->after('twitter_url');
            }
            if (! Schema::hasColumn('authors', 'facebook_url')) {
                $table->string('facebook_url', 255)->nullable()->after('linkedin_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            foreach (['twitter_url', 'linkedin_url', 'facebook_url'] as $column) {
                if (Schema::hasColumn('authors', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
