<?php
namespace App\Console\Commands;
use App\Models\Post;
use Illuminate\Console\Command;
class PublishScheduledPosts extends Command
{
    protected $signature = 'posts:publish-scheduled';
    protected $description = 'Publish posts that have been scheduled.';
    public function handle()
    {
        $postsToPublish = Post::where('status', 'scheduled')->where('published_at', '<=', now())->get();
        foreach ($postsToPublish as $post) { $post->update(['status' => 'published']); }
        $this->info(count($postsToPublish) . ' scheduled posts have been published.');
    }
}
