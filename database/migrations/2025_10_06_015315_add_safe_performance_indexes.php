<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to posts table (only if they don't exist)
        Schema::table('posts', function (Blueprint $table) {
            if (!$this->indexExists('posts', 'posts_status_index')) {
                $table->index('status');
            }
            if (!$this->indexExists('posts', 'posts_published_at_index')) {
                $table->index('published_at');
            }
            if (!$this->indexExists('posts', 'posts_category_id_index')) {
                $table->index('category_id');
            }
            if (!$this->indexExists('posts', 'posts_featured_image_id_index')) {
                $table->index('featured_image_id');
            }
        });

        // Add indexes to pages table
        Schema::table('pages', function (Blueprint $table) {
            if (!$this->indexExists('pages', 'pages_order_index')) {
                $table->index('order');
            }
        });

        // Add indexes to media table
        Schema::table('media', function (Blueprint $table) {
            if (!$this->indexExists('media', 'media_mime_type_index')) {
                $table->index('mime_type');
            }
            if (!$this->indexExists('media', 'media_created_at_index')) {
                $table->index('created_at');
            }
        });

        // Add indexes to categories table
        Schema::table('categories', function (Blueprint $table) {
            if (!$this->indexExists('categories', 'categories_slug_index')) {
                $table->index('slug');
            }
        });

        // Add indexes to menu_items table
        Schema::table('menu_items', function (Blueprint $table) {
            if (!$this->indexExists('menu_items', 'menu_items_order_index')) {
                $table->index('order');
            }
        });

        // Add indexes to settings table
        Schema::table('settings', function (Blueprint $table) {
            if (!$this->indexExists('settings', 'settings_key_index')) {
                $table->index('key');
            }
        });

        // Add indexes to newsletter_subscribers table
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            if (!$this->indexExists('newsletter_subscribers', 'newsletter_subscribers_email_index')) {
                $table->index('email');
            }
            if (!$this->indexExists('newsletter_subscribers', 'newsletter_subscribers_created_at_index')) {
                $table->index('created_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes from posts table
        Schema::table('posts', function (Blueprint $table) {
            if ($this->indexExists('posts', 'posts_status_index')) {
                $table->dropIndex(['status']);
            }
            if ($this->indexExists('posts', 'posts_published_at_index')) {
                $table->dropIndex(['published_at']);
            }
            if ($this->indexExists('posts', 'posts_category_id_index')) {
                $table->dropIndex(['category_id']);
            }
            if ($this->indexExists('posts', 'posts_featured_image_id_index')) {
                $table->dropIndex(['featured_image_id']);
            }
        });

        // Remove indexes from pages table
        Schema::table('pages', function (Blueprint $table) {
            if ($this->indexExists('pages', 'pages_order_index')) {
                $table->dropIndex(['order']);
            }
        });

        // Remove indexes from media table
        Schema::table('media', function (Blueprint $table) {
            if ($this->indexExists('media', 'media_mime_type_index')) {
                $table->dropIndex(['mime_type']);
            }
            if ($this->indexExists('media', 'media_created_at_index')) {
                $table->dropIndex(['created_at']);
            }
        });

        // Remove indexes from categories table
        Schema::table('categories', function (Blueprint $table) {
            if ($this->indexExists('categories', 'categories_slug_index')) {
                $table->dropIndex(['slug']);
            }
        });

        // Remove indexes from menu_items table
        Schema::table('menu_items', function (Blueprint $table) {
            if ($this->indexExists('menu_items', 'menu_items_order_index')) {
                $table->dropIndex(['order']);
            }
        });

        // Remove indexes from settings table
        Schema::table('settings', function (Blueprint $table) {
            if ($this->indexExists('settings', 'settings_key_index')) {
                $table->dropIndex(['key']);
            }
        });

        // Remove indexes from newsletter_subscribers table
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            if ($this->indexExists('newsletter_subscribers', 'newsletter_subscribers_email_index')) {
                $table->dropIndex(['email']);
            }
            if ($this->indexExists('newsletter_subscribers', 'newsletter_subscribers_created_at_index')) {
                $table->dropIndex(['created_at']);
            }
        });
    }

    /**
     * Check if an index exists on a table
     */
    private function indexExists(string $table, string $index): bool
    {
        $indexes = DB::select("SHOW INDEX FROM {$table}");
        foreach ($indexes as $idx) {
            if ($idx->Key_name === $index) {
                return true;
            }
        }
        return false;
    }
};