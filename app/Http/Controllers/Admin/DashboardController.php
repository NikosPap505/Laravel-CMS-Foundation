<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Media;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        // Content Statistics
        $totalPosts = Post::count();
        $publishedPosts = Post::where('status', 'published')->count();
        $scheduledPosts = Post::where('status', 'scheduled')->count();
        $draftPosts = Post::where('status', 'draft')->count();
        
        $totalPages = Page::count();
        $totalCategories = Category::count();
        $totalMedia = Media::count();
        $totalUsers = User::count();
        
        // Recent Posts
        $recentPosts = Post::with('category')
            ->latest()
            ->limit(5)
            ->get();
            
        // Popular Posts (by view count)
        $popularPosts = Post::published()
            ->popular(30)
            ->limit(5)
            ->get();
        
        // Popular Categories (by post count)
        $popularCategories = Category::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(5)
            ->get();
        
        // Recent Activity
        $recentActivity = Activity::with('causer', 'subject')
            ->latest()
            ->limit(10)
            ->get();
            
        // Pending Comments
        $pendingComments = \App\Models\Comment::pending()->with('post')->limit(5)->get();
        
        // Storage Usage
        $storageUsed = Media::sum('size');
        $storageUsedMB = round($storageUsed / 1024 / 1024, 2);
        
        // Posts by Status for Chart
        $postsByStatus = [
            'published' => $publishedPosts,
            'scheduled' => $scheduledPosts,
            'draft' => $draftPosts,
        ];
        
        // Recent Posts Chart Data (last 30 days)
        $recentPostsData = Post::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return view('admin.dashboard', compact(
            'totalPosts',
            'publishedPosts',
            'scheduledPosts',
            'draftPosts',
            'totalPages',
            'totalCategories',
            'totalMedia',
            'totalUsers',
            'recentPosts',
            'popularPosts',
            'popularCategories',
            'recentActivity',
            'pendingComments',
            'storageUsedMB',
            'postsByStatus',
            'recentPostsData'
        ));
    }
}
