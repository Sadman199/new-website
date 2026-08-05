<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('brokers', function (Blueprint $table) {
            $table->string('title')->nullable()->after('name'); // Adjust the 'after' column as needed
            $table->decimal('rating', 3, 2)->nullable()->after('title'); // e.g., max 5.00 rating
        });
    }

    public function down()
    {
        Schema::table('brokers', function (Blueprint $table) {
            $table->dropColumn(['title', 'rating']);
        });
    }
};
