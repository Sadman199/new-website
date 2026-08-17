<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating_cost')->nullable()->after('rating');
            $table->unsignedTinyInteger('rating_platforms')->nullable()->after('rating_cost');
            $table->unsignedTinyInteger('rating_customer_support')->nullable()->after('rating_platforms');
            $table->string('length_of_use')->nullable()->after('rating_customer_support');
            $table->string('account_type')->nullable()->after('length_of_use');
            $table->foreignId('parent_id')->nullable()->after('broker_id')->constrained('reviews')->cascadeOnDelete();
            $table->index(['broker_id', 'parent_id', 'status'], 'reviews_broker_parent_status_index');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_broker_parent_status_index');
            $table->dropConstrainedForeignId('parent_id');
            $table->dropColumn([
                'rating_cost',
                'rating_platforms',
                'rating_customer_support',
                'length_of_use',
                'account_type',
            ]);
        });
    }
};
