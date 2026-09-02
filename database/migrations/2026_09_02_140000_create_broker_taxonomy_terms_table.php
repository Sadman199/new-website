<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('broker_taxonomy_terms')) {
            return;
        }

        Schema::create('broker_taxonomy_terms', function (Blueprint $table) {
            $table->id();
            $table->string('type', 32);
            $table->string('slug', 80);
            $table->mediumText('description')->nullable();
            $table->timestamps();

            $table->unique(['type', 'slug']);
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('broker_taxonomy_terms');
    }
};
