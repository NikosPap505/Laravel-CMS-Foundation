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
        Schema::create('ai_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('operation_type'); // e.g., 'title_generation', 'content_improvement'
            $table->string('provider'); // e.g., 'openai', 'gemini'
            $table->integer('tokens_used')->default(0);
            $table->decimal('cost', 10, 4)->default(0.00);
            $table->string('status')->default('completed'); // completed, failed, pending
            $table->json('metadata')->nullable(); // Additional data like content length, etc.
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['operation_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_usage');
    }
};
