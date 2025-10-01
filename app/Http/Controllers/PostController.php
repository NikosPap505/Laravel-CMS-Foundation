<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        // CHANGE IS HERE: We added ->published() to only get published posts
        $posts = Post::published()->latest('published_at')->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function category(Category $category)
    {
        // CHANGE IS HERE: We added ->published() here as well
        $posts = $category->posts()->published()->latest('published_at')->paginate(10);

        return view('blog.category', compact('posts', 'category'));
    }

    public function show(Post $post)
    {
        // Optional: You might want to prevent direct access to non-published posts
        if ($post->status !== 'published' || $post->published_at->isFuture()) {
            abort(404);
        }

        return view('blog.show', compact('post'));
    }
}
