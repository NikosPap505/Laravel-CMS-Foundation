<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Add foreign key constraint to existing featured_image_id column
            $table->foreign('featured_image_id')->references('id')->on('media')->nullOnDelete();
        });

        // Drop legacy column if it exists
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'featured_image')) {
                $table->dropColumn('featured_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('featured_image')->nullable();
            $table->dropForeign(['featured_image_id']);
        });
    }
};


