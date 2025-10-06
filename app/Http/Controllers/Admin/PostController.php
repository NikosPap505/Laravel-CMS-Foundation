<?php

namespace App\Http\Controllers\Admin;

use App\Events\PostPublished;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

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

    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        $validated['body'] = clean($validated['body']);

        $post = Post::create($validated);
        
        // Fire event if the post was published immediately
        if ($post->status === 'published') {
            event(new PostPublished($post, false));
        }
        
        // Clear cache when new post is created
        clear_cms_cache();
        
        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $validated = $request->validated();
        $validated['body'] = clean($validated['body']);

        $wasPublished = $post->status === 'published';
        $post->update($validated);
        
        // Fire event if the post was just published (status changed to published)
        if (!$wasPublished && $post->status === 'published') {
            event(new PostPublished($post, false));
        }
        
        // Clear cache when post is updated
        clear_cms_cache();
        
        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        
        // Clear cache when post is deleted
        clear_cms_cache();

        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }
}