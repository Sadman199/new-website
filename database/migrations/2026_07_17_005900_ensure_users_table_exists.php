<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The stock create_users_table migration is recorded as "ran" from the earlier
 * SQLite era, but the table was never created in the current MySQL database.
 * This migration recreates the base users table only if it is missing, so the
 * subsequent profile-field / FK migrations can run.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Intentionally left blank: we do not want to drop a table that may
        // pre-date this migration.
    }
};
