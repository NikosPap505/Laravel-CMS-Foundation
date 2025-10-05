<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('featured_image_id')->nullable()->after('slug')->constrained('media')->nullOnDelete();
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
            $table->dropConstrainedForeignId('featured_image_id');
        });
    }
};


