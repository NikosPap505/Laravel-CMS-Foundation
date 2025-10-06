<?php

namespace App\Listeners;

use App\Events\PostPublished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ClearCacheOnPostPublish implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PostPublished $event): void
    {
        // Clear the CMS cache when a post is published
        if (function_exists('clear_cms_cache')) {
            clear_cms_cache();
        }

        // Log if this was an auto-published post
        if ($event->wasAutoPublished) {
            Log::info("Post auto-published and cache cleared", [
                'post_id' => $event->post->id,
                'post_title' => $event->post->title,
                'published_at' => $event->post->published_at?->toDateTimeString(),
            ]);
        }
    }
}
