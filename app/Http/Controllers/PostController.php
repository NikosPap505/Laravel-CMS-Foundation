<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('category')->published()->latest('published_at');

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('body', 'like', '%' . $searchTerm . '%');
            });
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

        $post->load('category');

        return view('blog.show', compact('post'));
    }
}
