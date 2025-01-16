<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('sub_categories', 'slug')) {
            Schema::table('sub_categories', function (Blueprint $table) {
                $table->string('slug')->unique()->after('sub_category_name');
            });
        }
    }
    
    public function down()
    {
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }


    
    
};
