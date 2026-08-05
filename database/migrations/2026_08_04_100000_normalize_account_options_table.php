<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('account_options', function (Blueprint $table) {
            if (! Schema::hasColumn('account_options', 'slug')) {
                $table->string('slug')->nullable()->after('account_type');
            }
            if (! Schema::hasColumn('account_options', 'sort_order')) {
                $table->unsignedSmallInteger('sort_order')->default(0)->after('account_type');
            }
            if (! Schema::hasColumn('account_options', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('account_type');
            }
            if (! Schema::hasColumn('account_options', 'max_leverage_numeric')) {
                $table->unsignedInteger('max_leverage_numeric')->nullable()->after('max_leverage');
            }
            if (! Schema::hasColumn('account_options', 'leverage_label')) {
                $table->string('leverage_label', 100)->nullable()->after('max_leverage_numeric');
            }
            if (! Schema::hasColumn('account_options', 'spread_from_pips')) {
                $table->decimal('spread_from_pips', 6, 2)->nullable()->after('spread_value');
            }
            if (! Schema::hasColumn('account_options', 'commission_per_lot')) {
                $table->decimal('commission_per_lot', 8, 2)->nullable()->after('commission');
            }
            if (! Schema::hasColumn('account_options', 'commission_label')) {
                $table->string('commission_label', 150)->nullable()->after('commission_per_lot');
            }
            if (! Schema::hasColumn('account_options', 'execution_model')) {
                $table->enum('execution_model', ['ecn', 'stp', 'market_maker', 'hybrid'])->nullable()->after('commission_label');
            }
            if (! Schema::hasColumn('account_options', 'ea_allowed')) {
                $table->boolean('ea_allowed')->default(true)->after('swap_free');
            }
            if (! Schema::hasColumn('account_options', 'hedging_allowed')) {
                $table->boolean('hedging_allowed')->default(true)->after('ea_allowed');
            }
            if (! Schema::hasColumn('account_options', 'vps_eligible')) {
                $table->boolean('vps_eligible')->default(false)->after('hedging_allowed');
            }
            if (! Schema::hasColumn('account_options', 'description')) {
                $table->text('description')->nullable()->after('access_to_pro_features');
            }
        });

        DB::table('account_options')->orderBy('id')->chunkById(100, function ($rows) {
            foreach ($rows as $row) {
                $updates = [];
                $rawLeverage = trim((string) ($row->max_leverage ?? ''));

                if (empty($row->max_leverage_numeric) && $rawLeverage !== '') {
                    $numeric = $this->parseLeverageNumeric($rawLeverage);
                    if ($numeric !== null) {
                        $updates['max_leverage_numeric'] = $numeric;
                    }
                }

                if (empty($row->leverage_label) && $rawLeverage !== '') {
                    if (! is_numeric($rawLeverage)) {
                        $updates['leverage_label'] = substr($rawLeverage, 0, 100);
                    } elseif (! empty($updates['max_leverage_numeric'])) {
                        $updates['leverage_label'] = '1:' . $updates['max_leverage_numeric'];
                    } elseif ($numeric = $this->parseLeverageNumeric($rawLeverage)) {
                        $updates['leverage_label'] = '1:' . $numeric;
                    }
                }

                if (empty($row->spread_from_pips) && $row->spread_value !== null && $row->spread_value !== '') {
                    $updates['spread_from_pips'] = $row->spread_value;
                }

                $rawCommission = trim((string) ($row->commission ?? ''));
                if (empty($row->commission_per_lot) && $rawCommission !== '' && is_numeric($rawCommission)) {
                    $updates['commission_per_lot'] = min((float) $rawCommission, 999999.99);
                }

                if (empty($row->commission_label) && $rawCommission !== '' && ! is_numeric($rawCommission)) {
                    $updates['commission_label'] = substr($rawCommission, 0, 150);
                }

                if ($updates !== []) {
                    DB::table('account_options')->where('id', $row->id)->update($updates);
                }
            }
        });

        $dropColumns = array_values(array_filter([
            Schema::hasColumn('account_options', 'is_regulated') ? 'is_regulated' : null,
            Schema::hasColumn('account_options', 'is_demo_available') ? 'is_demo_available' : null,
            Schema::hasColumn('account_options', 'account_management') ? 'account_management' : null,
            Schema::hasColumn('account_options', 'personalized_education') ? 'personalized_education' : null,
            Schema::hasColumn('account_options', 'exclusive_webinars') ? 'exclusive_webinars' : null,
            Schema::hasColumn('account_options', 'trading_instruments') ? 'trading_instruments' : null,
            Schema::hasColumn('account_options', 'risk_management_tools') ? 'risk_management_tools' : null,
            Schema::hasColumn('account_options', 'trading_hours') ? 'trading_hours' : null,
            Schema::hasColumn('account_options', 'interest_rate') ? 'interest_rate' : null,
        ]));

        if ($dropColumns !== []) {
            Schema::table('account_options', function (Blueprint $table) use ($dropColumns) {
                $table->dropColumn($dropColumns);
            });
        }
    }

    protected function parseLeverageNumeric(string $raw): ?int
    {
        if ($raw === '') {
            return null;
        }

        if (preg_match('/1\s*:\s*(\d+)/i', $raw, $matches)) {
            $numeric = (int) $matches[1];

            return ($numeric > 0 && $numeric <= 10000) ? $numeric : null;
        }

        if (preg_match('/(\d+)\s*:\s*1/i', $raw, $matches)) {
            $numeric = (int) $matches[1];

            return ($numeric > 0 && $numeric <= 10000) ? $numeric : null;
        }

        if (is_numeric($raw)) {
            $numeric = (int) $raw;

            return ($numeric > 0 && $numeric <= 10000) ? $numeric : null;
        }

        return null;
    }

    public function down(): void
    {
        Schema::table('account_options', function (Blueprint $table) {
            if (! Schema::hasColumn('account_options', 'is_regulated')) {
                $table->boolean('is_regulated')->default(false);
            }
            if (! Schema::hasColumn('account_options', 'is_demo_available')) {
                $table->boolean('is_demo_available')->default(false);
            }
            if (! Schema::hasColumn('account_options', 'account_management')) {
                $table->boolean('account_management')->default(false);
            }
            if (! Schema::hasColumn('account_options', 'personalized_education')) {
                $table->boolean('personalized_education')->default(false);
            }
            if (! Schema::hasColumn('account_options', 'exclusive_webinars')) {
                $table->boolean('exclusive_webinars')->default(false);
            }
            if (! Schema::hasColumn('account_options', 'trading_instruments')) {
                $table->json('trading_instruments')->nullable();
            }
            if (! Schema::hasColumn('account_options', 'risk_management_tools')) {
                $table->json('risk_management_tools')->nullable();
            }
            if (! Schema::hasColumn('account_options', 'trading_hours')) {
                $table->string('trading_hours')->nullable();
            }
            if (! Schema::hasColumn('account_options', 'interest_rate')) {
                $table->decimal('interest_rate', 5, 2)->nullable();
            }
        });

        $dropColumns = array_values(array_filter([
            Schema::hasColumn('account_options', 'slug') ? 'slug' : null,
            Schema::hasColumn('account_options', 'sort_order') ? 'sort_order' : null,
            Schema::hasColumn('account_options', 'is_active') ? 'is_active' : null,
            Schema::hasColumn('account_options', 'max_leverage_numeric') ? 'max_leverage_numeric' : null,
            Schema::hasColumn('account_options', 'leverage_label') ? 'leverage_label' : null,
            Schema::hasColumn('account_options', 'spread_from_pips') ? 'spread_from_pips' : null,
            Schema::hasColumn('account_options', 'commission_per_lot') ? 'commission_per_lot' : null,
            Schema::hasColumn('account_options', 'commission_label') ? 'commission_label' : null,
            Schema::hasColumn('account_options', 'execution_model') ? 'execution_model' : null,
            Schema::hasColumn('account_options', 'ea_allowed') ? 'ea_allowed' : null,
            Schema::hasColumn('account_options', 'hedging_allowed') ? 'hedging_allowed' : null,
            Schema::hasColumn('account_options', 'vps_eligible') ? 'vps_eligible' : null,
            Schema::hasColumn('account_options', 'description') ? 'description' : null,
        ]));

        if ($dropColumns !== []) {
            Schema::table('account_options', function (Blueprint $table) use ($dropColumns) {
                $table->dropColumn($dropColumns);
            });
        }
    }
};
