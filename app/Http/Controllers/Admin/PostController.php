<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('category')->latest()->paginate(15);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:posts',
        'category_id' => 'required|exists:categories,id',
        'excerpt' => 'required|string',
        'body' => 'required|string',
        'featured_image' => 'nullable|exists:media,id',
        'status' => 'required|in:draft,published,scheduled',
        'published_at' => 'nullable|date',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string',
    ]);

    if ($request->filled('featured_image')) {
        $media = \App\Models\Media::find($request->input('featured_image'));
        $validated['featured_image'] = $media->path;
    }

    if ($validated['status'] === 'published' && empty($validated['published_at'])) {
        $validated['published_at'] = now();
    }

    $validated['body'] = clean($validated['body']);

    Post::create($validated);
    return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
}

    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

public function update(Request $request, Post $post)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:posts,slug,' . $post->id,
        'category_id' => 'required|exists:categories,id',
        'excerpt' => 'required|string',
        'body' => 'required|string',
        'featured_image' => 'nullable|exists:media,id',
        'status' => 'required|in:draft,published,scheduled',
        'published_at' => 'nullable|date',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string',
    ]);

    if ($request->filled('featured_image')) {
        $media = \App\Models\Media::find($request->input('featured_image'));
        $validated['featured_image'] = $media->path;
    } else {
        $validated['featured_image'] = null;
    }

    if ($validated['status'] === 'published' && empty($validated['published_at'])) {
        $validated['published_at'] = now();
    }
    
    if ($validated['status'] === 'draft') {
        $validated['published_at'] = null;
    }

    $validated['body'] = clean($validated['body']);

    $post->update($validated);
    return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
}

    public function destroy(Post $post)
    {
        // Delete the featured image from storage
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }
}