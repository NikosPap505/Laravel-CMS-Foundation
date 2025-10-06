<?php

namespace App\Console\Commands;

use App\Events\PostPublished;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PublishScheduledPosts extends Command
{
    protected $signature = 'posts:publish-scheduled';
    
    protected $description = 'Publish posts that have been scheduled.';

    public function handle(): int
    {
        // Get IDs first for logging and events
        $postIds = Post::where('status', 'scheduled')
            ->where('published_at', '<=', now())
            ->pluck('id');

        $count = $postIds->count();

        if ($count === 0) {
            $this->info('No scheduled posts to publish.');
            return Command::SUCCESS;
        }

        // Bulk update for performance
        Post::whereIn('id', $postIds)
            ->update(['status' => 'published']);

        // Fire events for each published post
        foreach ($postIds as $postId) {
            $post = Post::find($postId);
            if ($post) {
                event(new PostPublished($post, true));
            }
        }

        // Clear cache
        if (function_exists('clear_cms_cache')) {
            clear_cms_cache();
        }

        // Log the action
        Log::info("Auto-published {$count} scheduled posts", [
            'post_ids' => $postIds->toArray(),
            'timestamp' => now()->toDateTimeString(),
        ]);

        $this->info("{$count} scheduled post" . ($count !== 1 ? 's' : '') . " published successfully.");

        return Command::SUCCESS;
    }
}
