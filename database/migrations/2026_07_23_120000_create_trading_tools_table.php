<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('trading_tools')) {
            Schema::create('trading_tools', function (Blueprint $table) {
                $table->id();
                $table->string('slug', 50)->unique();
                $table->string('name');
                $table->string('icon', 80)->nullable()->default('fas fa-calculator');
                $table->string('short_description')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        $now = now();
        $tools = [
            ['slug' => 'pip', 'name' => 'Pip Calculator', 'icon' => 'fas fa-exchange-alt', 'short_description' => 'Pip value and position notional', 'sort_order' => 1],
            ['slug' => 'position', 'name' => 'Position Size', 'icon' => 'fas fa-layer-group', 'short_description' => 'Size lots from risk & stop loss', 'sort_order' => 2],
            ['slug' => 'profit', 'name' => 'Profit / Loss', 'icon' => 'fas fa-chart-line', 'short_description' => 'Estimate trade P/L in pips & money', 'sort_order' => 3],
            ['slug' => 'margin', 'name' => 'Margin Calculator', 'icon' => 'fas fa-percentage', 'short_description' => 'Required margin by leverage', 'sort_order' => 4],
            ['slug' => 'risk', 'name' => 'Risk Calculator', 'icon' => 'fas fa-shield-alt', 'short_description' => 'Risk amount from balance & %', 'sort_order' => 5],
            ['slug' => 'pivot', 'name' => 'Pivot Points', 'icon' => 'fas fa-crosshairs', 'short_description' => 'Classic / Fibonacci pivot levels', 'sort_order' => 6],
            ['slug' => 'fibonacci', 'name' => 'Fibonacci', 'icon' => 'fas fa-wave-square', 'short_description' => 'Retracement & extension levels', 'sort_order' => 7],
            ['slug' => 'converter', 'name' => 'Currency Converter', 'icon' => 'fas fa-coins', 'short_description' => 'Convert between major currencies', 'sort_order' => 8],
        ];

        foreach ($tools as $tool) {
            $exists = DB::table('trading_tools')->where('slug', $tool['slug'])->exists();
            if ($exists) {
                continue;
            }
            DB::table('trading_tools')->insert(array_merge($tool, [
                'description' => $tool['short_description'],
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('trading_tools');
    }
};
