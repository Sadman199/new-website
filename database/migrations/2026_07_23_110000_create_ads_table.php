<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ads')) {
            return;
        }

        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['banner', 'text', 'image', 'video', 'custom', 'popup'])->default('banner');
            $table->string('image')->nullable();
            $table->longText('html_code')->nullable();
            $table->string('video_url')->nullable();
            $table->string('link')->nullable();
            $table->text('description')->nullable();
            $table->string('position', 50)->nullable()->default('sidebar');
            $table->boolean('is_active')->nullable()->default(true);
            $table->integer('priority')->nullable()->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('trigger_type', ['time', 'scroll', 'stay'])->nullable()->default('time')
                ->comment('Trigger type: time in seconds, scroll %, or stay in minutes');
            $table->integer('trigger_value')->nullable()
                ->comment('Value for trigger: seconds, % or minutes depending on trigger_type');
            $table->boolean('repeatable')->nullable()->default(false)
                ->comment('0 = show once per user/session, 1 = allow multiple times');
            $table->string('category', 100)->nullable()
                ->comment('Campaign or folder for grouping popups');
            $table->json('pages')->nullable()
                ->comment('Target pages or URLs in JSON array');
            $table->timestamps();

            $table->index(['type', 'is_active']);
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
