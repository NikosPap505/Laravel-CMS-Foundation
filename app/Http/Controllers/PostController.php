<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('category', 'featuredImage')->published()->latest('published_at');

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            // Use full-text search for better performance
            $query->whereFullText(['title', 'excerpt', 'body'], $searchTerm);
        }

        $posts = $query->paginate(9)->withQueryString();

        return view('blog.index', compact('posts'));
    }

    public function category(Category $category)
    {
        // Eager load the 'category' relationship
        $posts = $category->posts()->with('category')->published()->latest('published_at')->paginate(10);

        return view('blog.category', compact('posts', 'category'));
    }

    public function show(Post $post)
    {
        // Optional: You might want to prevent direct access to non-published posts
        if ($post->status !== 'published' || $post->published_at->isFuture()) {
            abort(404);
        }

        $post->load('category', 'tags');
        $relatedPosts = $post->related(3);
        
        // Load approved comments with replies
        $comments = $post->approvedComments()
            ->with(['user', 'replies.user', 'replies.replies.user'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Increment view count
        $post->incrementViewCount();

        return view('blog.show', compact('post', 'relatedPosts', 'comments'));
    }

    public function feed()
    {
        $posts = Post::with('category', 'featuredImage')
            ->published()
            ->latest('published_at')
            ->limit(20)
            ->get();

        return response()->view('blog.feed', compact('posts'))
            ->header('Content-Type', 'application/xml');
    }
}
