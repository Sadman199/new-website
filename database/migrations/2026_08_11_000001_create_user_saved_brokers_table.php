<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_saved_brokers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('broker_id')->constrained('brokers')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'broker_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_saved_brokers');
    }
};
