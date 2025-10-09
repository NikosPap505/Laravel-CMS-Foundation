<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    /**
     * Switch user theme preference
     */
    public function switch(Request $request): JsonResponse
    {
        $availableThemes = array_keys(config('themes.available_themes', []));
        $themeValidation = 'required|in:' . implode(',', $availableThemes);

        $request->validate([
            'theme' => $themeValidation
        ]);

        $user = Auth::user();
        $user->theme_preference = $request->theme;
        $user->save();

        return response()->json([
            'success' => true,
            'theme' => $request->theme,
            'message' => 'Theme updated successfully'
        ]);
    }

    /**
     * Get current user theme preference
     */
    public function current(): JsonResponse
    {
        $theme = Auth::user()->theme_preference ?? config('themes.default_theme', 'light');

        return response()->json([
            'theme' => $theme
        ]);
    }

    /**
     * Get all available themes
     */
    public function index(): JsonResponse
    {
        $themes = config('themes.available_themes', []);
        $categories = config('themes.theme_categories', []);

        return response()->json([
            'themes' => $themes,
            'categories' => $categories,
            'current_theme' => Auth::user()->theme_preference ?? config('themes.default_theme', 'light')
        ]);
    }

    /**
     * Get theme details
     */
    public function show(string $theme): JsonResponse
    {
        $themes = config('themes.available_themes', []);

        if (!isset($themes[$theme])) {
            return response()->json([
                'error' => 'Theme not found'
            ], 404);
        }

        return response()->json([
            'theme' => $themes[$theme]
        ]);
    }
}
