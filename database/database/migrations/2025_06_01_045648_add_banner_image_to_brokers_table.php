<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('brokers', function (Blueprint $table) {
            $table->string('banner_image_1')->nullable()->after('logo');
            $table->string('banner_image_2')->nullable()->after('banner_image_1');
        });
    }

    public function down()
    {
        Schema::table('brokers', function (Blueprint $table) {
            $table->dropColumn('banner_image_1');
            $table->dropColumn('banner_image_2');
        });
    }
};
