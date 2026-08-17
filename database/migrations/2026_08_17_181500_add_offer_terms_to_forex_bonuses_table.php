<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forex_bonuses', function (Blueprint $table) {
            $table->string('wagering_requirement')->nullable()->after('bonus_percentage');
            $table->decimal('max_credit', 12, 2)->nullable()->after('wagering_requirement');
            $table->string('eligible_clients', 32)->nullable()->after('max_credit');
            $table->string('volume_requirement')->nullable()->after('eligible_clients');
        });
    }

    public function down(): void
    {
        Schema::table('forex_bonuses', function (Blueprint $table) {
            $table->dropColumn([
                'wagering_requirement',
                'max_credit',
                'eligible_clients',
                'volume_requirement',
            ]);
        });
    }
};
