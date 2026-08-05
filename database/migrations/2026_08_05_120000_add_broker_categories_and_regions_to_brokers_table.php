<?php

use App\Models\Broker;
use App\Support\BrokerTaxonomy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brokers', function (Blueprint $table) {
            $table->json('broker_categories')->nullable()->after('account_types');
            $table->json('regions')->nullable()->after('broker_categories');
        });

        if (! Schema::hasColumn('brokers', 'broker_categories')) {
            return;
        }

        Broker::query()->each(function (Broker $broker) {
            [$legacyCategories, $labels] = BrokerTaxonomy::splitLegacyAccountTypes(
                is_array($broker->account_types) ? $broker->account_types : null
            );

            $storedCategories = is_array($broker->broker_categories) ? $broker->broker_categories : [];
            $mergedCategories = array_values(array_unique(array_merge($storedCategories, $legacyCategories)));

            if ($mergedCategories !== [] || $labels !== $broker->account_types) {
                $broker->broker_categories = $mergedCategories ?: null;
                $broker->account_types = $labels ?: null;
                $broker->saveQuietly();
            }
        });
    }

    public function down(): void
    {
        Schema::table('brokers', function (Blueprint $table) {
            $table->dropColumn(['broker_categories', 'regions']);
        });
    }
};
