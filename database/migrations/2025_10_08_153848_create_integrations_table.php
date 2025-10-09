<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('category');
            $table->json('config')->nullable();
            $table->boolean('is_connected')->default(false);
            $table->string('status')->default('disconnected');
            $table->timestamp('last_sync')->nullable();
            $table->integer('error_count')->default(0);
            $table->float('success_rate')->default(100);
            $table->float('response_time')->default(0);
            $table->json('webhook_config')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['category', 'is_connected']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};
