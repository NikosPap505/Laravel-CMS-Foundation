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
        Schema::table('ai_usage', function (Blueprint $table) {
            $table->decimal('success_rate', 5, 2)->nullable()->after('metadata');
            $table->unsignedBigInteger('content_id')->nullable()->after('success_rate');
            $table->string('content_type')->nullable()->after('content_id');
            $table->boolean('suggestion_accepted')->default(false)->after('content_type');

            $table->index(['user_id', 'operation_type']);
            $table->index(['content_id', 'content_type']);
            $table->index('suggestion_accepted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_usage', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'operation_type']);
            $table->dropIndex(['content_id', 'content_type']);
            $table->dropIndex(['suggestion_accepted']);

            $table->dropColumn(['success_rate', 'content_id', 'content_type', 'suggestion_accepted']);
        });
    }
};
