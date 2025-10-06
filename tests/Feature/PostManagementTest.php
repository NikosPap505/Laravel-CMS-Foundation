<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PostManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Create permissions
        \Spatie\Permission\Models\Permission::create(['name' => 'manage posts']);
        \Spatie\Permission\Models\Permission::create(['name' => 'manage categories']);
        \Spatie\Permission\Models\Permission::create(['name' => 'manage pages']);
        
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(\Spatie\Permission\Models\Permission::all());
        
        Role::create(['name' => 'editor']);
        
        // Create user and assign admin role
        $this->user = User::factory()->create();
        $this->user->assignRole('admin');
        
        // Create a category
        $this->category = Category::factory()->create();
    }

    public function test_authenticated_user_can_view_posts_index(): void
    {
        $response = $this->actingAs($this->user)->get('/admin/posts');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.posts.index');
    }

    public function test_authenticated_user_can_create_post(): void
    {
        $postData = [
            'title' => 'Test Post',
            'slug' => 'test-post',
            'category_id' => $this->category->id,
            'excerpt' => 'This is a test excerpt',
            'body' => 'This is the test post body content.',
            'status' => 'published',
            'meta_title' => 'Test Meta Title',
            'meta_description' => 'Test meta description',
        ];

        $response = $this->actingAs($this->user)->post('/admin/posts', $postData);
        
        $response->assertRedirect('/admin/posts');
        $response->assertSessionHas('success', 'Post created successfully.');
        
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'slug' => 'test-post',
            'status' => 'published',
        ]);
    }

    public function test_post_creation_requires_valid_data(): void
    {
        $response = $this->actingAs($this->user)->post('/admin/posts', []);
        
        $response->assertSessionHasErrors(['title', 'slug', 'category_id', 'excerpt', 'body', 'status']);
    }

    public function test_post_slug_must_be_unique(): void
    {
        Post::factory()->create(['slug' => 'existing-post']);
        
        $postData = [
            'title' => 'Test Post',
            'slug' => 'existing-post', // Duplicate slug
            'category_id' => $this->category->id,
            'excerpt' => 'This is a test excerpt',
            'body' => 'This is the test post body content.',
            'status' => 'published',
        ];

        $response = $this->actingAs($this->user)->post('/admin/posts', $postData);
        
        $response->assertSessionHasErrors(['slug']);
    }

    public function test_authenticated_user_can_edit_post(): void
    {
        $post = Post::factory()->create(['category_id' => $this->category->id]);
        
        $updateData = [
            'title' => 'Updated Post Title',
            'slug' => 'updated-post-slug',
            'category_id' => $this->category->id,
            'excerpt' => 'Updated excerpt',
            'body' => 'Updated body content',
            'status' => 'published',
        ];

        $response = $this->actingAs($this->user)->put("/admin/posts/{$post->id}", $updateData);
        
        $response->assertRedirect('/admin/posts');
        $response->assertSessionHas('success', 'Post updated successfully.');
        
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Post Title',
            'slug' => 'updated-post-slug',
        ]);
    }

    public function test_authenticated_user_can_delete_post(): void
    {
        $post = Post::factory()->create(['category_id' => $this->category->id]);
        
        $response = $this->actingAs($this->user)->delete("/admin/posts/{$post->id}");
        
        $response->assertRedirect('/admin/posts');
        $response->assertSessionHas('success', 'Post deleted successfully.');
        
        // Check that post is soft deleted
        $this->assertSoftDeleted('posts', ['id' => $post->id]);
    }

    public function test_published_posts_scope_works_correctly(): void
    {
        // Create published post
        $publishedPost = Post::factory()->create([
            'category_id' => $this->category->id,
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
        
        // Create draft post
        $draftPost = Post::factory()->create([
            'category_id' => $this->category->id,
            'status' => 'draft',
            'published_at' => null,
        ]);
        
        // Create scheduled post
        $scheduledPost = Post::factory()->create([
            'category_id' => $this->category->id,
            'status' => 'scheduled',
            'published_at' => now()->addDay(),
        ]);

        $publishedPosts = Post::published()->get();
        
        $this->assertCount(1, $publishedPosts);
        $this->assertEquals($publishedPost->id, $publishedPosts->first()->id);
    }

    public function test_post_belongs_to_category(): void
    {
        $post = Post::factory()->create(['category_id' => $this->category->id]);
        
        $this->assertInstanceOf(Category::class, $post->category);
        $this->assertEquals($this->category->id, $post->category->id);
    }

    public function test_guest_cannot_access_admin_posts(): void
    {
        $response = $this->get('/admin/posts');
        $response->assertRedirect('/login');
    }

    public function test_post_auto_sets_published_at_when_status_is_published(): void
    {
        $postData = [
            'title' => 'Test Post',
            'slug' => 'test-post',
            'category_id' => $this->category->id,
            'excerpt' => 'This is a test excerpt',
            'body' => 'This is the test post body content.',
            'status' => 'published',
        ];

        $this->actingAs($this->user)->post('/admin/posts', $postData);
        
        $post = Post::where('slug', 'test-post')->first();
        $this->assertNotNull($post->published_at);
    }
}