<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PageManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'editor']);
        
        // Create user and assign admin role
        $this->user = User::factory()->create();
        $this->user->assignRole('admin');
    }

    public function test_authenticated_user_can_view_pages_index(): void
    {
        $response = $this->actingAs($this->user)->get('/admin/pages');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.pages.index');
    }

    public function test_authenticated_user_can_create_page(): void
    {
        $pageData = [
            'title' => 'Test Page',
            'slug' => 'test-page',
            'content' => 'This is the test page content.',
            'meta_title' => 'Test Meta Title',
            'meta_description' => 'Test meta description',
        ];

        $response = $this->actingAs($this->user)->post('/admin/pages', $pageData);
        
        $response->assertRedirect('/admin/pages');
        $response->assertSessionHas('success', 'Page created successfully!');
        
        $this->assertDatabaseHas('pages', [
            'title' => 'Test Page',
            'slug' => 'test-page',
        ]);
    }

    public function test_page_creation_requires_valid_data(): void
    {
        $response = $this->actingAs($this->user)->post('/admin/pages', []);
        
        $response->assertSessionHasErrors(['title', 'slug']);
    }

    public function test_page_slug_must_be_unique(): void
    {
        Page::factory()->create(['slug' => 'existing-page']);
        
        $pageData = [
            'title' => 'Test Page',
            'slug' => 'existing-page', // Duplicate slug
            'content' => 'This is the test page content.',
        ];

        $response = $this->actingAs($this->user)->post('/admin/pages', $pageData);
        
        $response->assertSessionHasErrors(['slug']);
    }

    public function test_authenticated_user_can_edit_page(): void
    {
        $page = Page::factory()->create();
        
        $updateData = [
            'title' => 'Updated Page Title',
            'slug' => 'updated-page-slug',
            'content' => 'Updated page content',
            'meta_title' => 'Updated Meta Title',
            'meta_description' => 'Updated meta description',
        ];

        $response = $this->actingAs($this->user)->put("/admin/pages/{$page->id}", $updateData);
        
        $response->assertRedirect('/admin/pages');
        $response->assertSessionHas('success', 'Page updated successfully!');
        
        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'title' => 'Updated Page Title',
            'slug' => 'updated-page-slug',
        ]);
    }

    public function test_authenticated_user_can_delete_page(): void
    {
        $page = Page::factory()->create();
        
        $response = $this->actingAs($this->user)->delete("/admin/pages/{$page->id}");
        
        $response->assertRedirect('/admin/pages');
        $response->assertSessionHas('success', 'Page deleted successfully!');
        
        // Check that page is soft deleted
        $this->assertSoftDeleted('pages', ['id' => $page->id]);
    }

    public function test_guest_cannot_access_admin_pages(): void
    {
        $response = $this->get('/admin/pages');
        $response->assertRedirect('/login');
    }

    public function test_public_page_route_works(): void
    {
        $page = Page::factory()->create([
            'slug' => 'test-public-page',
            'title' => 'Test Public Page',
            'content' => 'This is public page content.',
        ]);

        $response = $this->get('/test-public-page');
        
        $response->assertStatus(200);
        $response->assertViewIs('page.show');
        $response->assertViewHas('page', $page);
    }

    public function test_page_reorder_functionality(): void
    {
        $page1 = Page::factory()->create(['order' => 1]);
        $page2 = Page::factory()->create(['order' => 2]);
        $page3 = Page::factory()->create(['order' => 3]);

        $reorderData = [
            'order' => [$page3->id, $page1->id, $page2->id]
        ];

        $response = $this->actingAs($this->user)->post('/admin/pages/reorder', $reorderData);
        
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        // Check that order was updated
        $this->assertEquals(1, Page::find($page3->id)->order);
        $this->assertEquals(2, Page::find($page1->id)->order);
        $this->assertEquals(3, Page::find($page2->id)->order);
    }
}