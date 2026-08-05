<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugToPostsTable extends Migration
{
    public function up()
    {
        // Check if 'slug' column exists before attempting to add it
        if (!Schema::hasColumn('posts', 'slug')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->string('slug')->unique()->after('post_title');
            });
        }
    }

    public function down()
    {
        // Check if 'slug' column exists before attempting to drop it
        if (Schema::hasColumn('posts', 'slug')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
}
