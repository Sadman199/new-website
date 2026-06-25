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
        Schema::create('account_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broker_id')->constrained()->onDelete('cascade'); // Foreign key to brokers table
            $table->string('account_type'); // E.g., Standard, Premium, etc.
            $table->string('account_currency'); // Currency for the account (e.g., USD, EUR)
            $table->decimal('min_deposit', 10, 2); // Minimum deposit for this account type
            $table->float('max_leverage'); // Maximum leverage for this account type
            $table->string('spread_type'); // E.g., Fixed, Variable spread
            $table->decimal('spread_value', 5, 2); // Spread value for this account type
            $table->boolean('is_demo_available')->default(false); // Whether demo account is available
            $table->json('features')->nullable(); // Additional features (e.g., social trading, educational resources)
            $table->boolean('swap_free')->default(false); // Whether the account is swap-free
            $table->decimal('min_trade_size', 10, 2); // Minimum trade size
            $table->decimal('max_trade_size', 10, 2); // Maximum trade size
            $table->decimal('margin_call_level', 5, 2); // Margin call level for the account type
            $table->decimal('stop_out_level', 5, 2); // Stop-out level for the account type
            $table->integer('max_open_positions'); // Maximum number of open positions allowed
            $table->decimal('commission', 10, 2)->nullable(); // Commission per trade (if any)
            $table->decimal('interest_rate', 5, 2)->nullable(); // Interest rate for overnight financing (if any)
            $table->boolean('access_to_pro_features')->default(false); // Access to professional features
            $table->text('exclusive_offers')->nullable(); // Special offers for this account type
            $table->boolean('account_management')->default(false); // Whether the account has a dedicated account manager
            $table->json('trading_instruments')->nullable(); // List of available trading instruments
            $table->json('risk_management_tools')->nullable(); // Available risk management tools
            $table->boolean('bonus_eligibility')->default(false); // Whether the account is eligible for bonuses
            $table->boolean('personalized_education')->default(false); // Whether the account includes personalized education
            $table->boolean('exclusive_webinars')->default(false); // Whether the account provides access to exclusive webinars
            $table->decimal('maximum_daily_trade_volume', 15, 2)->nullable(); // Maximum daily trade volume allowed
            $table->string('trading_hours'); // Trading hours for this account type (e.g., 24/5, 24/7)
            $table->text('special_conditions')->nullable(); // Special conditions or requirements for this account
            $table->boolean('is_regulated')->default(false); // Whether the account type is regulated or not
            $table->timestamps(); // Timestamps (created_at, updated_at)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_type');
    }
};
