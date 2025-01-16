<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToBrokersTable extends Migration
{
    public function up()
    {
        Schema::table('brokers', function (Blueprint $table) {
            $table->string('slug')->after('id'); // Slug field for URL-friendly name
            $table->text('top_feature')->nullable()->after('news_and_analysis'); // Top feature textarea
            $table->boolean('featured_broker')->default(false)->after('top_feature'); // Featured broker check mark
            $table->integer('top_broker')->nullable()->after('featured_broker'); // Top broker number field
            $table->string('meta_title')->nullable()->after('top_broker'); // Meta Title
            $table->text('meta_keyword')->nullable()->after('meta_title'); // Meta Keyword
            $table->text('meta_description')->nullable()->after('meta_keyword'); // Meta Description
        });
    }

    public function down()
    {
        Schema::table('brokers', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'top_feature',
                'featured_broker',
                'top_broker',
                'meta_title',
                'meta_keyword',
                'meta_description'
            ]);
        });
    }
}