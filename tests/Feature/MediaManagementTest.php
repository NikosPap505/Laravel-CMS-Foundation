<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MediaManagementTest extends TestCase
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
        
        // Fake storage
        Storage::fake('public');
    }

    public function test_authenticated_user_can_view_media_index(): void
    {
        $response = $this->actingAs($this->user)->get('/admin/media');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.media.index');
    }

    public function test_authenticated_user_can_upload_media(): void
    {
        $file = UploadedFile::fake()->create('test-document.pdf', 1000, 'application/pdf');
        
        $response = $this->actingAs($this->user)->post('/admin/media', [
            'file' => $file,
        ]);
        
        $response->assertRedirect('/admin/media');
        $response->assertSessionHas('success', 'Media uploaded successfully.');
        
        // Check that file was stored
        $this->assertTrue(Storage::disk('public')->exists('media/' . $file->hashName()));
        
        // Check that media record was created
        $this->assertDatabaseHas('media', [
            'name' => 'test-document.pdf',
            'mime_type' => 'application/pdf',
        ]);
    }

    public function test_media_upload_requires_file(): void
    {
        $response = $this->actingAs($this->user)->post('/admin/media', []);
        
        $response->assertSessionHasErrors(['file']);
    }

    public function test_media_upload_has_file_size_limit(): void
    {
        // Create a file larger than 10MB
        $file = UploadedFile::fake()->create('large-file.jpg', 11000); // 11MB
        
        $response = $this->actingAs($this->user)->post('/admin/media', [
            'file' => $file,
        ]);
        
        $response->assertSessionHasErrors(['file']);
    }

    public function test_authenticated_user_can_edit_media(): void
    {
        $media = Media::factory()->create();
        
        $updateData = [
            'name' => 'Updated Media Name',
            'alt_text' => 'Updated alt text',
            'caption' => 'Updated caption',
        ];

        $response = $this->actingAs($this->user)->put("/admin/media/{$media->id}", $updateData);
        
        $response->assertRedirect("/admin/media/{$media->id}/edit");
        $response->assertSessionHas('success', 'Media updated successfully.');
        
        $this->assertDatabaseHas('media', [
            'id' => $media->id,
            'name' => 'Updated Media Name',
            'alt_text' => 'Updated alt text',
            'caption' => 'Updated caption',
        ]);
    }

    public function test_authenticated_user_can_delete_media(): void
    {
        $media = Media::factory()->create();
        
        // Create the actual file in storage
        Storage::disk('public')->put($media->path, 'fake content');
        
        $response = $this->actingAs($this->user)->delete("/admin/media/{$media->id}");
        
        $response->assertRedirect('/admin/media');
        $response->assertSessionHas('success', 'Media deleted successfully.');
        
        // Check that media is soft deleted
        $this->assertSoftDeleted('media', ['id' => $media->id]);
        
        // Check that file was deleted from storage
        $this->assertFalse(Storage::disk('public')->exists($media->path));
    }

    public function test_media_api_endpoint_returns_correct_data(): void
    {
        $media = Media::factory()->create([
            'name' => 'test-image.jpg',
            'alt_text' => 'Test image',
        ]);
        
        $response = $this->actingAs($this->user)->get('/admin/api/media');
        
        $response->assertStatus(200);
        $response->assertJson([
            [
                'id' => $media->id,
                'name' => 'test-image.jpg',
                'alt_text' => 'Test image',
            ]
        ]);
    }

    public function test_guest_cannot_access_admin_media(): void
    {
        $response = $this->get('/admin/media');
        $response->assertRedirect('/login');
    }

    public function test_media_upload_handles_different_file_types(): void
    {
        $textFile = UploadedFile::fake()->create('test.txt', 100, 'text/plain');
        $pdfFile = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');
        
        // Test text file upload
        $response = $this->actingAs($this->user)->post('/admin/media', [
            'file' => $textFile,
        ]);
        
        $response->assertRedirect('/admin/media');
        
        // Test PDF upload
        $response = $this->actingAs($this->user)->post('/admin/media', [
            'file' => $pdfFile,
        ]);
        
        $response->assertRedirect('/admin/media');
        
        // Check both files were stored
        $this->assertTrue(Storage::disk('public')->exists('media/' . $textFile->hashName()));
        $this->assertTrue(Storage::disk('public')->exists('media/' . $pdfFile->hashName()));
    }
}