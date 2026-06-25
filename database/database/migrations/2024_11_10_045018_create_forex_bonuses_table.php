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
        Schema::create('forex_bonuses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('publish_date');
            $table->string('author_name');
            $table->enum('promo_type', [
                'Forex Deposit Bonus',
                'Forex No Deposit Bonus',
                'Forex Live Contest',
                'Forex Demo Contest',
                'Forex Cashback Rebate',
                'Crypto Bonus Promotion'
            ]);
            $table->text('description');
            $table->string('feature_image');
            $table->string('link');
            $table->text('participate');
            $table->text('how_to_participate');
            $table->text('details');
            $table->text('general_terms');
            $table->string('prize');
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
        Schema::dropIfExists('forex_bonuses');
    }
};
