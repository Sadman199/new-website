<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToForexBonusesTable extends Migration
{
    public function up()
    {
        Schema::table('forex_bonuses', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
            $table->text('eligibility_criteria')->nullable()->after('description');
            $table->date('expiry_date')->nullable()->after('eligibility_criteria');
            $table->decimal('min_deposit', 10, 2)->nullable()->after('expiry_date');
            $table->text('bonus_type_details')->nullable()->after('min_deposit');
            $table->string('terms_conditions_url')->nullable()->after('bonus_type_details');
            $table->string('affiliate_link')->nullable()->after('terms_conditions_url');
            $table->string('bonus_category')->nullable()->after('affiliate_link');
            $table->enum('promotion_status', ['ongoing', 'limited-time', 'expired'])->default('ongoing');
        });
    }

    public function down()
    {
        Schema::table('forex_bonuses', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'eligibility_criteria',
                'expiry_date',
                'min_deposit',
                'bonus_type_details',
                'terms_conditions_url',
                'affiliate_link',
                'bonus_category',
                'promotion_status',
            ]);
        });
    }
}
