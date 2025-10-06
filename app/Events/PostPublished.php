<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostPublished
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param Post $post The post that was published
     * @param bool $wasAutoPublished Whether the post was automatically published by the scheduler
     */
    public function __construct(
        public Post $post,
        public bool $wasAutoPublished = false
    ) {
        //
    }
}
