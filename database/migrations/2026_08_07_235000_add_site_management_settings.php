<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'theme_color_3')) {
                $table->text('theme_color_3')->nullable()->after('theme_color_2');
            }
            if (! Schema::hasColumn('settings', 'site_name')) {
                $table->text('site_name')->nullable()->after('theme_color_3');
            }
            if (! Schema::hasColumn('settings', 'site_tagline')) {
                $table->text('site_tagline')->nullable()->after('site_name');
            }
            if (! Schema::hasColumn('settings', 'contact_phone')) {
                $table->text('contact_phone')->nullable()->after('site_tagline');
            }
            if (! Schema::hasColumn('settings', 'footer_copyright')) {
                $table->text('footer_copyright')->nullable()->after('contact_phone');
            }
            if (! Schema::hasColumn('settings', 'default_meta_description')) {
                $table->text('default_meta_description')->nullable()->after('footer_copyright');
            }
            if (! Schema::hasColumn('settings', 'maintenance_mode')) {
                $table->text('maintenance_mode')->nullable()->after('default_meta_description');
            }
            if (! Schema::hasColumn('settings', 'maintenance_message')) {
                $table->text('maintenance_message')->nullable()->after('maintenance_mode');
            }
            if (! Schema::hasColumn('settings', 'show_broker_spotlight')) {
                $table->text('show_broker_spotlight')->nullable()->after('maintenance_message');
            }
            if (! Schema::hasColumn('settings', 'show_quick_access_drawer')) {
                $table->text('show_quick_access_drawer')->nullable()->after('show_broker_spotlight');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            foreach ([
                'theme_color_3',
                'site_name',
                'site_tagline',
                'contact_phone',
                'footer_copyright',
                'default_meta_description',
                'maintenance_mode',
                'maintenance_message',
                'show_broker_spotlight',
                'show_quick_access_drawer',
            ] as $column) {
                if (Schema::hasColumn('settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
