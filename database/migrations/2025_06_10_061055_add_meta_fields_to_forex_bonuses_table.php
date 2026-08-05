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
        Schema::table('forex_bonuses', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('prize');
            $table->string('meta_keywords')->nullable()->after('meta_title');
            $table->text('meta_description')->nullable()->after('meta_keywords');
        });
    }

    public function down()
    {
        Schema::table('forex_bonuses', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_keywords', 'meta_description']);
        });
    }

};
