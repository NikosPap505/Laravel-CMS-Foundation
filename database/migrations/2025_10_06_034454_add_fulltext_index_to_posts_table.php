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
        // Add full-text index for faster search queries
        Schema::table('posts', function (Blueprint $table) {
            $table->fullText(['title', 'excerpt', 'body'], 'posts_fulltext_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropFullText('posts_fulltext_index');
        });
    }
};
