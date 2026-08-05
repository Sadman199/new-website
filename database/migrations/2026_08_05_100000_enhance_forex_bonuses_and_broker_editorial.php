<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected function editorialColumns(Blueprint $table): void
    {
        if (! Schema::hasColumn($table->getTable(), 'written_by_author_id')) {
            $table->unsignedBigInteger('written_by_author_id')->nullable();
        }
        if (! Schema::hasColumn($table->getTable(), 'edited_by_author_id')) {
            $table->unsignedBigInteger('edited_by_author_id')->nullable();
        }
        if (! Schema::hasColumn($table->getTable(), 'fact_checked_by_author_id')) {
            $table->unsignedBigInteger('fact_checked_by_author_id')->nullable();
        }
        if (! Schema::hasColumn($table->getTable(), 'written_by_admin_id')) {
            $table->unsignedBigInteger('written_by_admin_id')->nullable();
        }
        if (! Schema::hasColumn($table->getTable(), 'edited_by_admin_id')) {
            $table->unsignedBigInteger('edited_by_admin_id')->nullable();
        }
        if (! Schema::hasColumn($table->getTable(), 'fact_checked_by_admin_id')) {
            $table->unsignedBigInteger('fact_checked_by_admin_id')->nullable();
        }
    }

    public function up(): void
    {
        Schema::table('forex_bonuses', function (Blueprint $table) {
            if (! Schema::hasColumn('forex_bonuses', 'broker_id')) {
                $table->foreignId('broker_id')->nullable()->after('slug')->constrained('brokers')->nullOnDelete();
            }
            if (! Schema::hasColumn('forex_bonuses', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('promotion_status');
            }
            if (! Schema::hasColumn('forex_bonuses', 'bonus_amount')) {
                $table->decimal('bonus_amount', 12, 2)->nullable()->after('min_deposit');
            }
            if (! Schema::hasColumn('forex_bonuses', 'bonus_percentage')) {
                $table->decimal('bonus_percentage', 5, 2)->nullable()->after('bonus_amount');
            }
        });

        Schema::table('forex_bonuses', function (Blueprint $table) {
            $this->editorialColumns($table);
        });

        Schema::table('brokers', function (Blueprint $table) {
            $this->editorialColumns($table);
        });
    }

    public function down(): void
    {
        $dropEditorial = fn (Blueprint $table, string $name) => array_filter([
            Schema::hasColumn($name, 'written_by_author_id') ? 'written_by_author_id' : null,
            Schema::hasColumn($name, 'edited_by_author_id') ? 'edited_by_author_id' : null,
            Schema::hasColumn($name, 'fact_checked_by_author_id') ? 'fact_checked_by_author_id' : null,
            Schema::hasColumn($name, 'written_by_admin_id') ? 'written_by_admin_id' : null,
            Schema::hasColumn($name, 'edited_by_admin_id') ? 'edited_by_admin_id' : null,
            Schema::hasColumn($name, 'fact_checked_by_admin_id') ? 'fact_checked_by_admin_id' : null,
        ]);

        Schema::table('forex_bonuses', function (Blueprint $table) use ($dropEditorial) {
            if (Schema::hasColumn('forex_bonuses', 'broker_id')) {
                $table->dropForeign(['broker_id']);
            }

            $cols = array_values(array_filter(array_merge(
                $dropEditorial($table, 'forex_bonuses'),
                [
                    Schema::hasColumn('forex_bonuses', 'broker_id') ? 'broker_id' : null,
                    Schema::hasColumn('forex_bonuses', 'is_featured') ? 'is_featured' : null,
                    Schema::hasColumn('forex_bonuses', 'bonus_amount') ? 'bonus_amount' : null,
                    Schema::hasColumn('forex_bonuses', 'bonus_percentage') ? 'bonus_percentage' : null,
                ]
            )));

            if ($cols !== []) {
                $table->dropColumn($cols);
            }
        });

        Schema::table('brokers', function (Blueprint $table) use ($dropEditorial) {
            $cols = $dropEditorial($table, 'brokers');
            if ($cols !== []) {
                $table->dropColumn($cols);
            }
        });
    }
};
