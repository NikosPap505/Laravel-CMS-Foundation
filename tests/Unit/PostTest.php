<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_belongs_to_category(): void
    {
        $category = Category::factory()->create();
        $post = Post::factory()->create(['category_id' => $category->id]);
        
        $this->assertInstanceOf(Category::class, $post->category);
        $this->assertEquals($category->id, $post->category->id);
    }

    public function test_post_belongs_to_featured_image(): void
    {
        $media = Media::factory()->create();
        $post = Post::factory()->create(['featured_image_id' => $media->id]);
        
        $this->assertInstanceOf(Media::class, $post->featuredImage);
        $this->assertEquals($media->id, $post->featuredImage->id);
    }

    public function test_post_can_have_null_featured_image(): void
    {
        $post = Post::factory()->create(['featured_image_id' => null]);
        
        $this->assertNull($post->featuredImage);
    }

    public function test_published_scope_filters_correctly(): void
    {
        $category = Category::factory()->create();
        
        // Create published post
        $publishedPost = Post::factory()->create([
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
        
        // Create draft post
        $draftPost = Post::factory()->create([
            'category_id' => $category->id,
            'status' => 'draft',
            'published_at' => null,
        ]);
        
        // Create scheduled post (future date)
        $scheduledPost = Post::factory()->create([
            'category_id' => $category->id,
            'status' => 'scheduled',
            'published_at' => now()->addDay(),
        ]);
        
        // Create published post with future date (should not be included)
        $futurePublishedPost = Post::factory()->create([
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now()->addDay(),
        ]);

        $publishedPosts = Post::published()->get();
        
        $this->assertCount(1, $publishedPosts);
        $this->assertEquals($publishedPost->id, $publishedPosts->first()->id);
    }

    public function test_post_has_correct_fillable_attributes(): void
    {
        $post = new Post();
        $fillable = $post->getFillable();
        
        $expectedFillable = [
            'category_id',
            'title',
            'slug',
            'excerpt',
            'body',
            'featured_image_id',
            'status',
            'published_at',
            'meta_title',
            'meta_description',
            'view_count',
        ];
        
        $this->assertEquals($expectedFillable, $fillable);
    }

    public function test_post_casts_published_at_to_datetime(): void
    {
        $post = Post::factory()->create([
            'published_at' => '2023-01-01 12:00:00',
        ]);
        
        $this->assertInstanceOf(\Carbon\Carbon::class, $post->published_at);
    }

    public function test_post_soft_deletes(): void
    {
        $category = Category::factory()->create();
        $post = Post::factory()->create(['category_id' => $category->id]);
        
        $post->delete();
        
        $this->assertSoftDeleted('posts', ['id' => $post->id]);
        
        // Post should still exist in database but with deleted_at timestamp
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_post_can_be_restored_after_soft_delete(): void
    {
        $category = Category::factory()->create();
        $post = Post::factory()->create(['category_id' => $category->id]);
        
        $post->delete();
        $this->assertSoftDeleted('posts', ['id' => $post->id]);
        
        $post->restore();
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'deleted_at' => null,
        ]);
    }
}