<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Notifications\AdminNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        // Rate limiting: 5 comments per minute per IP
        $key = 'comment:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many comments. Please wait a moment.',
            ], 429);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:2000|min:10',
            'parent_id' => 'nullable|exists:comments,id',
            'author_name' => 'required_if:is_guest,true|string|max:255',
            'author_email' => 'required_if:is_guest,true|email|max:255',
        ]);

        // Check if user is authenticated
        $isGuest = !Auth::check();
        
        $commentData = [
            'post_id' => $post->id,
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_guest' => $isGuest,
        ];

        if ($isGuest) {
            $commentData['author_name'] = $validated['author_name'];
            $commentData['author_email'] = $validated['author_email'];
        } else {
            $commentData['user_id'] = Auth::id();
        }

        // Auto-approve comments from authenticated users
        $commentData['status'] = $isGuest ? 'pending' : 'approved';

        $comment = Comment::create($commentData);

        // Hit rate limiter
        RateLimiter::hit($key, 60);

        // Send notification to admins for new comments
        $adminUsers = User::role('admin')->get();
        foreach ($adminUsers as $admin) {
            $admin->notify(new AdminNotification(
                'new_comment',
                'New Comment on "' . $post->title . '"',
                $isGuest 
                    ? "New comment from {$validated['author_name']}: " . substr($validated['content'], 0, 100) . "..."
                    : "New comment from " . Auth::user()->name . ": " . substr($validated['content'], 0, 100) . "...",
                route('admin.comments.index'),
                [
                    'comment_id' => $comment->id,
                    'post_id' => $post->id,
                    'post_title' => $post->title,
                ]
            ));
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $isGuest 
                    ? 'Your comment has been submitted and is awaiting moderation.'
                    : 'Your comment has been posted successfully.',
                'comment' => $comment->load('user', 'replies'),
            ]);
        }

        return redirect()->back()->with('success', 
            $isGuest 
                ? 'Your comment has been submitted and is awaiting moderation.'
                : 'Your comment has been posted successfully.'
        );
    }

    public function loadComments(Post $post)
    {
        $comments = $post->approvedComments()
            ->with(['user', 'replies.user', 'replies.replies.user'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'comments' => $comments,
        ]);
    }
}