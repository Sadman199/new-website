<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('broker_taxonomy_terms') || ! Schema::hasColumn('broker_taxonomy_terms', 'description')) {
            return;
        }

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `broker_taxonomy_terms` MODIFY `description` MEDIUMTEXT NULL');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('broker_taxonomy_terms') || ! Schema::hasColumn('broker_taxonomy_terms', 'description')) {
            return;
        }

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `broker_taxonomy_terms` MODIFY `description` TEXT NULL');
        }
    }
};
