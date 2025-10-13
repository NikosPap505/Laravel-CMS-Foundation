<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('category', 'featuredImage')->published();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            // Use full-text search on MySQL/MariaDB; fallback to LIKE on SQLite
            if (DB::getDriverName() === 'sqlite') {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('excerpt', 'like', '%' . $searchTerm . '%')
                      ->orWhere('body', 'like', '%' . $searchTerm . '%');
                });
            } else {
                $query->whereFullText(['title', 'excerpt', 'body'], $searchTerm);
            }
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Sort functionality
        switch ($request->get('sort', 'latest')) {
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            case 'latest':
            default:
                $query->latest('published_at');
                break;
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
