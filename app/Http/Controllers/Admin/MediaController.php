<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $path = $file->store('media', 'public');

        Media::create([
            'name' => $file->getClientOriginalName(),
            'file_name' => $file->hashName(),
            'mime_type' => $file->getMimeType(),
            'path' => $path,
            'size' => $file->getSize(),
        ]);

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
