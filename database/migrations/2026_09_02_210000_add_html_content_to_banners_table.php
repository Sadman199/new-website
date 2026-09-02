<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('banners')) {
            return;
        }

        Schema::table('banners', function (Blueprint $table) {
            if (! Schema::hasColumn('banners', 'creative_format')) {
                $table->string('creative_format', 20)->default('image')->after('title');
            }
            if (! Schema::hasColumn('banners', 'html_content')) {
                $table->longText('html_content')->nullable()->after('mobile_image');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('banners')) {
            return;
        }

        Schema::table('banners', function (Blueprint $table) {
            if (Schema::hasColumn('banners', 'html_content')) {
                $table->dropColumn('html_content');
            }
            if (Schema::hasColumn('banners', 'creative_format')) {
                $table->dropColumn('creative_format');
            }
        });
    }
};
