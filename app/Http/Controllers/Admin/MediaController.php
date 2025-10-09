<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::latest()->paginate(20);
        return view('admin.media.index', compact('media'));
    }

    public function create()
    {
        return view('admin.media.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'max:10240', // 10MB Max
                'mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,mp4,mp3',
                'mimetypes:image/jpeg,image/png,image/gif,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,text/plain,video/mp4,audio/mpeg'
            ],
        ]);

        $file = $request->file('file');

        // Additional security checks
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt', 'mp4', 'mp3'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowedExtensions)) {
            return redirect()->back()->withErrors(['file' => 'File type not allowed.']);
        }

        // Check file size
        if ($file->getSize() > 10 * 1024 * 1024) { // 10MB
            return redirect()->back()->withErrors(['file' => 'File size too large. Maximum 10MB allowed.']);
        }

        // Try to optimize images (requires GD or Imagick extension)
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $optimized = false;

        if (in_array($extension, $imageExtensions)) {
            try {
                // Try to optimize the image
                $image = Image::read($file);

                // Resize if too large (max 1920px width)
                if ($image->width() > 1920) {
                    $image->scale(width: 1920);
                }

                // Encode with optimized quality
                $encoded = $image->encode();

                // Store the optimized image
                $filename = $file->hashName();
                $path = 'media/' . $filename;
                Storage::disk('public')->put($path, $encoded);

                $size = strlen($encoded);
                $mimeType = $file->getMimeType();
                $optimized = true;
            } catch (\Exception $e) {
                // If optimization fails (no GD/Imagick), store normally
                logger()->warning('Image optimization failed, storing original: ' . $e->getMessage());
            }
        }

        // If not optimized (either not an image or optimization failed), store normally
        if (!$optimized) {
            $path = $file->store('media', 'public');
            $size = $file->getSize();
            $mimeType = $file->getMimeType();
            $filename = $file->hashName();
        }

        $media = Media::create([
            'name' => $file->getClientOriginalName(),
            'file_name' => $filename,
            'mime_type' => $mimeType,
            'path' => $path,
            'size' => $size,
        ]);

        // Return JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Media uploaded successfully.',
                'media' => [
                    'id' => $media->id,
                    'name' => $media->name,
                    'url' => asset('storage/' . $media->path),
                    'alt_text' => $media->alt_text,
                    'mime_type' => $media->mime_type,
                    'size' => $media->size,
                ]
            ]);
        }

        return redirect()->route('admin.media.index')->with('success', 'Media uploaded successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Media $medium)
    {
        return view('admin.media.edit', ['medium' => $medium]);
    }

    public function update(Request $request, Media $medium)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'caption' => 'nullable|string',
        ]);

        $medium->update($validated);

        return redirect()->route('admin.media.edit', $medium)->with('success', 'Media updated successfully.');
    }

    public function destroy(Media $medium)
    {
        Storage::disk('public')->delete($medium->path);
        $medium->delete();

        return redirect()->route('admin.media.index')->with('success', 'Media deleted successfully.');
    }

    public function apiIndex()
    {
        return Media::latest()->get()->map(function ($medium) {
            return [
                'id' => $medium->id,
                'name' => $medium->name,
                'url' => asset('storage/' . $medium->path),
                'alt_text' => $medium->alt_text,
            ];
        });
    }
}
