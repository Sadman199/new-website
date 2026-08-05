<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('brokers', function (Blueprint $table) {
            if (!Schema::hasColumn('brokers', 'is_scam')) {
                $table->boolean('is_scam')->default(false)->index()->after('top_broker');
            }
            if (!Schema::hasColumn('brokers', 'scam_reason')) {
                $table->text('scam_reason')->nullable()->after('is_scam');
            }
            if (!Schema::hasColumn('brokers', 'scam_reported_date')) {
                $table->date('scam_reported_date')->nullable()->after('scam_reason');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brokers', function (Blueprint $table) {
            foreach (['is_scam', 'scam_reason', 'scam_reported_date'] as $column) {
                if (Schema::hasColumn('brokers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
