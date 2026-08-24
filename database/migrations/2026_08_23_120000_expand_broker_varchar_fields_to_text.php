<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            'languages',
            'pricing',
            'deposit_methods',
            'withdrawal_method',
            'spreads',
        ] as $column) {
            if (Schema::hasColumn('brokers', $column)) {
                DB::statement("ALTER TABLE `brokers` MODIFY `{$column}` TEXT NULL");
            }
        }
    }

    public function down(): void
    {
        foreach ([
            'languages',
            'pricing',
            'deposit_methods',
            'withdrawal_method',
            'spreads',
        ] as $column) {
            if (Schema::hasColumn('brokers', $column)) {
                DB::statement("ALTER TABLE `brokers` MODIFY `{$column}` VARCHAR(255) NULL");
            }
        }
    }
};
