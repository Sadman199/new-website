<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            if (! Schema::hasColumn('authors', 'can_write')) {
                $table->boolean('can_write')->default(true)->after('token');
            }
            if (! Schema::hasColumn('authors', 'can_edit')) {
                $table->boolean('can_edit')->default(false)->after('can_write');
            }
            if (! Schema::hasColumn('authors', 'can_fact_check')) {
                $table->boolean('can_fact_check')->default(false)->after('can_edit');
            }
            if (! Schema::hasColumn('authors', 'bio')) {
                $table->text('bio')->nullable()->after('can_fact_check');
            }
        });

        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'written_by_author_id')) {
                $table->unsignedBigInteger('written_by_author_id')->nullable()->after('author');
            }
            if (! Schema::hasColumn('posts', 'edited_by_author_id')) {
                $table->unsignedBigInteger('edited_by_author_id')->nullable()->after('written_by_author_id');
            }
            if (! Schema::hasColumn('posts', 'fact_checked_by_author_id')) {
                $table->unsignedBigInteger('fact_checked_by_author_id')->nullable()->after('edited_by_author_id');
            }
            if (! Schema::hasColumn('posts', 'written_by_admin_id')) {
                $table->unsignedBigInteger('written_by_admin_id')->nullable()->after('fact_checked_by_author_id');
            }
            if (! Schema::hasColumn('posts', 'edited_by_admin_id')) {
                $table->unsignedBigInteger('edited_by_admin_id')->nullable()->after('written_by_admin_id');
            }
            if (! Schema::hasColumn('posts', 'fact_checked_by_admin_id')) {
                $table->unsignedBigInteger('fact_checked_by_admin_id')->nullable()->after('edited_by_admin_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('posts', 'written_by_author_id') ? 'written_by_author_id' : null,
                Schema::hasColumn('posts', 'edited_by_author_id') ? 'edited_by_author_id' : null,
                Schema::hasColumn('posts', 'fact_checked_by_author_id') ? 'fact_checked_by_author_id' : null,
                Schema::hasColumn('posts', 'written_by_admin_id') ? 'written_by_admin_id' : null,
                Schema::hasColumn('posts', 'edited_by_admin_id') ? 'edited_by_admin_id' : null,
                Schema::hasColumn('posts', 'fact_checked_by_admin_id') ? 'fact_checked_by_admin_id' : null,
            ]);

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('authors', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('authors', 'can_write') ? 'can_write' : null,
                Schema::hasColumn('authors', 'can_edit') ? 'can_edit' : null,
                Schema::hasColumn('authors', 'can_fact_check') ? 'can_fact_check' : null,
                Schema::hasColumn('authors', 'bio') ? 'bio' : null,
            ]);

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
