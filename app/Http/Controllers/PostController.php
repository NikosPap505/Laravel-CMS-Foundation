<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('category')->latest()->paginate(10);

        return view('blog.index', compact('posts'));
    }

    public function show(Post $post)
    {
        return view('blog.show', compact('post'));
    }

    public function category(Category $category)
    {
        $posts = $category->posts()->latest()->paginate(10);

        return view('blog.index', compact('posts', 'category'));
    }
}