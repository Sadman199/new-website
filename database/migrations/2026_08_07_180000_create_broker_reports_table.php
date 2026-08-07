<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('broker_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broker_id')->nullable()->constrained('brokers')->nullOnDelete();
            $table->string('broker_name')->nullable();
            $table->string('reporter_name');
            $table->string('reporter_email');
            $table->string('issue_type');
            $table->text('message');
            $table->string('status')->default('pending');
            $table->text('admin_notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('broker_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('broker_reports');
    }
};
