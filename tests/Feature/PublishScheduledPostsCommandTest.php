<?php

namespace Tests\Feature;

use App\Events\PostPublished;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PublishScheduledPostsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_publishes_scheduled_posts_with_past_date(): void
    {
        $category = Category::factory()->create();
        
        $scheduledPost = Post::factory()->create([
            'category_id' => $category->id,
            'status' => 'scheduled',
            'published_at' => now()->subMinutes(5),
        ]);

        $this->artisan('posts:publish-scheduled')
            ->expectsOutput('1 scheduled post published successfully.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('posts', [
            'id' => $scheduledPost->id,
            'status' => 'published',
        ]);
    }

    public function test_does_not_publish_scheduled_posts_with_future_date(): void
    {
        $category = Category::factory()->create();
        
        $scheduledPost = Post::factory()->create([
            'category_id' => $category->id,
            'status' => 'scheduled',
            'published_at' => now()->addHours(2),
        ]);

        $this->artisan('posts:publish-scheduled')
            ->expectsOutput('No scheduled posts to publish.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('posts', [
            'id' => $scheduledPost->id,
            'status' => 'scheduled',
        ]);
    }

    public function test_does_not_affect_draft_or_published_posts(): void
    {
        $category = Category::factory()->create();
        
        $draftPost = Post::factory()->create([
            'category_id' => $category->id,
            'status' => 'draft',
            'published_at' => now()->subDay(),
        ]);

        $publishedPost = Post::factory()->create([
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $this->artisan('posts:publish-scheduled')
            ->assertExitCode(0);

        $this->assertDatabaseHas('posts', [
            'id' => $draftPost->id,
            'status' => 'draft',
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $publishedPost->id,
            'status' => 'published',
        ]);
    }

    public function test_publishes_multiple_scheduled_posts(): void
    {
        $category = Category::factory()->create();
        
        $post1 = Post::factory()->create([
            'category_id' => $category->id,
            'status' => 'scheduled',
            'published_at' => now()->subMinutes(10),
        ]);

        $post2 = Post::factory()->create([
            'category_id' => $category->id,
            'status' => 'scheduled',
            'published_at' => now()->subMinutes(5),
        ]);

        $post3 = Post::factory()->create([
            'category_id' => $category->id,
            'status' => 'scheduled',
            'published_at' => now()->subMinute(),
        ]);

        $this->artisan('posts:publish-scheduled')
            ->expectsOutput('3 scheduled posts published successfully.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('posts', [
            'id' => $post1->id,
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post2->id,
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post3->id,
            'status' => 'published',
        ]);
    }

    public function test_fires_post_published_event_when_auto_publishing(): void
    {
        Event::fake();
        
        $category = Category::factory()->create();
        
        $scheduledPost = Post::factory()->create([
            'category_id' => $category->id,
            'status' => 'scheduled',
            'published_at' => now()->subMinutes(5),
        ]);

        $this->artisan('posts:publish-scheduled')
            ->assertExitCode(0);

        Event::assertDispatched(PostPublished::class, function ($event) use ($scheduledPost) {
            return $event->post->id === $scheduledPost->id && $event->wasAutoPublished === true;
        });
    }

    public function test_logs_published_posts(): void
    {
        Log::shouldReceive('info')
            ->once()
            ->with('Auto-published 1 scheduled posts', \Mockery::type('array'));
        
        Log::shouldReceive('info')
            ->once()
            ->with('Post auto-published and cache cleared', \Mockery::type('array'));

        $category = Category::factory()->create();
        
        $scheduledPost = Post::factory()->create([
            'category_id' => $category->id,
            'status' => 'scheduled',
            'published_at' => now()->subMinutes(5),
        ]);

        $this->artisan('posts:publish-scheduled')
            ->assertExitCode(0);
    }

    public function test_handles_exact_current_time_as_publishable(): void
    {
        $category = Category::factory()->create();
        
        // Create post with published_at set to exactly now
        $scheduledPost = Post::factory()->create([
            'category_id' => $category->id,
            'status' => 'scheduled',
            'published_at' => now(),
        ]);

        $this->artisan('posts:publish-scheduled')
            ->expectsOutput('1 scheduled post published successfully.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('posts', [
            'id' => $scheduledPost->id,
            'status' => 'published',
        ]);
    }

    public function test_correct_pluralization_in_output(): void
    {
        $category = Category::factory()->create();
        
        // Test singular
        $post = Post::factory()->create([
            'category_id' => $category->id,
            'status' => 'scheduled',
            'published_at' => now()->subMinute(),
        ]);

        $this->artisan('posts:publish-scheduled')
            ->expectsOutput('1 scheduled post published successfully.')
            ->assertExitCode(0);
    }
}
