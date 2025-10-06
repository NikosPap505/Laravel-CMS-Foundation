<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['post', 'user', 'parent']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by post
        if ($request->filled('post_id')) {
            $query->where('post_id', $request->post_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%")
                  ->orWhere('author_email', 'like', "%{$search}%");
            });
        }

        $comments = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    public function show(Comment $comment)
    {
        $comment->load(['post', 'user', 'parent', 'replies.user']);
        return view('admin.comments.show', compact('comment'));
    }

    public function approve(Comment $comment)
    {
        $comment->approve();
        
        return redirect()->back()->with('success', 'Comment approved successfully.');
    }

    public function reject(Comment $comment)
    {
        $comment->reject();
        
        return redirect()->back()->with('success', 'Comment rejected successfully.');
    }

    public function markAsSpam(Comment $comment)
    {
        $comment->markAsSpam();
        
        return redirect()->back()->with('success', 'Comment marked as spam.');
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject,spam,delete',
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:comments,id',
        ]);

        $comments = Comment::whereIn('id', $validated['comment_ids']);

        switch ($validated['action']) {
            case 'approve':
                $comments->update(['status' => 'approved']);
                $message = 'Comments approved successfully.';
                break;
            case 'reject':
                $comments->update(['status' => 'rejected']);
                $message = 'Comments rejected successfully.';
                break;
            case 'spam':
                $comments->update(['status' => 'spam']);
                $message = 'Comments marked as spam.';
                break;
            case 'delete':
                $comments->delete();
                $message = 'Comments deleted successfully.';
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        
        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment deleted successfully.');
    }
}