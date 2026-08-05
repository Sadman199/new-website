<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brokers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->string('logo')->nullable();
            $table->text('short_description')->nullable();
            $table->string('visit_site')->nullable();
            $table->string('open_live')->nullable();
            $table->string('open_demo')->nullable();
            $table->text('pros')->nullable();
            $table->text('cons')->nullable();
            $table->string('languages')->nullable();
            $table->string('pricing')->nullable();
            $table->string('deposit_methods')->nullable(); // Supports both text or image paths
            $table->string('withdrawal_method')->nullable(); // Supports both text or image paths
            $table->string('country');
            $table->text('regulation')->nullable();
            $table->text('regulated_jurisdictions')->nullable();
            $table->text('regulatory_licenses')->nullable();
            $table->decimal('minimum_deposit', 10, 2)->nullable();
            $table->string('spreads')->nullable();
            $table->text('leverage')->nullable();
            $table->text('platforms')->nullable();
            $table->text('payment_methods')->nullable();
            $table->text('customer_support')->nullable();
            $table->text('educational_resources')->nullable();
            $table->text('research_tools')->nullable();
            $table->text('mobile_trading')->nullable();
            $table->text('social_trading')->nullable();
            $table->text('account_types')->nullable();
            $table->decimal('capitalization', 15, 2)->nullable();
            $table->text('insurance')->nullable();
            $table->boolean('segregation_of_funds')->default(false);
            $table->text('web_trader')->nullable();
            $table->text('charting_tools')->nullable();
            $table->boolean('account_managers')->default(false);
            $table->text('news_and_analysis')->nullable();
            $table->boolean('economic_calendar')->default(false);
            $table->boolean('vps_hosting')->default(false);
            $table->json('associated_countries')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brokers');
    }
};
