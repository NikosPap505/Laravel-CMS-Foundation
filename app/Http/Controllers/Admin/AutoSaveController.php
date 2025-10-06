<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AutoSaveController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'nullable|exists:posts,id',
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:1000',
            'body' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $cacheKey = 'autosave_post_' . ($validated['post_id'] ?? 'new') . '_' . auth()->id();
        
        // Store in cache for 1 hour
        Cache::put($cacheKey, $validated, 3600);

        return response()->json([
            'success' => true,
            'message' => 'Draft auto-saved',
            'timestamp' => now()->format('H:i:s'),
        ]);
    }

    public function load(Request $request)
    {
        $postId = $request->input('post_id');
        $cacheKey = 'autosave_post_' . ($postId ?? 'new') . '_' . auth()->id();
        
        $draft = Cache::get($cacheKey);
        
        if ($draft) {
            return response()->json([
                'success' => true,
                'draft' => $draft,
                'timestamp' => now()->format('H:i:s'),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No draft found',
        ]);
    }

    public function clear(Request $request)
    {
        $postId = $request->input('post_id');
        $cacheKey = 'autosave_post_' . ($postId ?? 'new') . '_' . auth()->id();
        
        Cache::forget($cacheKey);

        return response()->json([
            'success' => true,
            'message' => 'Draft cleared',
        ]);
    }
}